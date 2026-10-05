<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Academic\Models\OnlineClass;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ZoomController extends Controller
{
    private $apiKey;
    private $apiSecret;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('zoom.client_id');
        $this->apiSecret = config('zoom.client_secret');
        $this->baseUrl = config('zoom.base_url');
    }

    /**
     * Create a Zoom meeting
     */
    public function createMeeting(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after:now',
            'duration' => 'required|integer|min:1|max:1440',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'password' => 'nullable|string|min:6|max:10',
            'waiting_room' => 'boolean',
            'recording' => 'boolean',
        ]);

        try {
            $accessToken = $this->getAccessToken();
            
            $meetingData = [
                'topic' => $request->title,
                'type' => 2, // Scheduled meeting
                'start_time' => Carbon::parse($request->start_time)->format('Y-m-d\TH:i:s\Z'),
                'duration' => $request->duration,
                'timezone' => config('app.timezone', 'UTC'),
                'password' => $request->password ?? $this->generatePassword(),
                'agenda' => $request->description,
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'cn_meeting' => false,
                    'in_meeting' => false,
                    'join_before_host' => false,
                    'mute_upon_entry' => false,
                    'watermark' => false,
                    'use_pmi' => false,
                    'approval_type' => 0,
                    'audio' => 'both',
                    'auto_recording' => $request->recording ? 'cloud' : 'none',
                    'enforce_login' => false,
                    'waiting_room' => $request->waiting_room ?? false,
                    'request_permission_to_unmute_participants' => false,
                    'registrants_email_notification' => true,
                ],
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/users/me/meetings', $meetingData);

            if ($response->successful()) {
                $meeting = $response->json();
                
                // Create online class record
                $onlineClass = OnlineClass::create([
                    'school_id' => Auth::user()->school_id,
                    'teacher_id' => Auth::id(),
                    'title' => $request->title,
                    'description' => $request->description,
                    'academic_class_id' => $request->academic_class_id,
                    'subject_id' => $request->subject_id,
                    'start_time' => $request->start_time,
                    'end_time' => Carbon::parse($request->start_time)->addMinutes($request->duration),
                    'meeting_link' => $meeting['join_url'],
                    'meeting_id' => $meeting['id'],
                    'meeting_password' => $meeting['password'],
                    'max_participants' => $meeting['settings']['max_participants'] ?? 100,
                    'is_recording_allowed' => $request->recording ?? false,
                    'instructions' => $request->description,
                    'status' => 'scheduled',
                    'platform' => 'zoom',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Zoom meeting created successfully',
                    'meeting' => [
                        'id' => $meeting['id'],
                        'join_url' => $meeting['join_url'],
                        'password' => $meeting['password'],
                        'start_time' => $meeting['start_time'],
                        'duration' => $meeting['duration'],
                    ],
                    'online_class' => $onlineClass->load(['teacher', 'academicClass', 'subject']),
                ]);
            } else {
                Log::error('Zoom API Error: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create Zoom meeting: ' . $response->json('message', 'Unknown error'),
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Zoom Meeting Creation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Zoom meeting: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update a Zoom meeting
     */
    public function updateMeeting(Request $request, OnlineClass $onlineClass): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after:now',
            'duration' => 'required|integer|min:1|max:1440',
            'password' => 'nullable|string|min:6|max:10',
        ]);

        try {
            $accessToken = $this->getAccessToken();
            
            $meetingData = [
                'topic' => $request->title,
                'start_time' => Carbon::parse($request->start_time)->format('Y-m-d\TH:i:s\Z'),
                'duration' => $request->duration,
                'timezone' => config('app.timezone', 'UTC'),
                'password' => $request->password ?? $onlineClass->meeting_password,
                'agenda' => $request->description,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->patch($this->baseUrl . '/meetings/' . $onlineClass->meeting_id, $meetingData);

            if ($response->successful()) {
                // Update online class record
                $onlineClass->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'start_time' => $request->start_time,
                    'end_time' => Carbon::parse($request->start_time)->addMinutes($request->duration),
                    'meeting_password' => $request->password ?? $onlineClass->meeting_password,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Zoom meeting updated successfully',
                    'online_class' => $onlineClass->load(['teacher', 'academicClass', 'subject']),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update Zoom meeting: ' . $response->json('message', 'Unknown error'),
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Zoom Meeting Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Zoom meeting: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a Zoom meeting
     */
    public function deleteMeeting(OnlineClass $onlineClass): JsonResponse
    {
        try {
            $accessToken = $this->getAccessToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->delete($this->baseUrl . '/meetings/' . $onlineClass->meeting_id);

            if ($response->successful() || $response->status() === 404) {
                // Delete online class record
                $onlineClass->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Zoom meeting deleted successfully',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete Zoom meeting: ' . $response->json('message', 'Unknown error'),
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Zoom Meeting Delete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Zoom meeting: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get meeting details
     */
    public function getMeetingDetails(OnlineClass $onlineClass): JsonResponse
    {
        try {
            $accessToken = $this->getAccessToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get($this->baseUrl . '/meetings/' . $onlineClass->meeting_id);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'meeting' => $response->json(),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to get meeting details: ' . $response->json('message', 'Unknown error'),
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error('Zoom Meeting Details Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get meeting details: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get access token for Zoom API using OAuth 2.0
     */
    private function getAccessToken(): string
    {
        $accountId = config('zoom.account_id');
        $clientId = config('zoom.client_id');
        $clientSecret = config('zoom.client_secret');
        
        // Create base64 encoded credentials
        $credentials = base64_encode($clientId . ':' . $clientSecret);
        
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->asForm()->post('https://zoom.us/oauth/token', [
            'grant_type' => 'account_credentials',
            'account_id' => $accountId,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['access_token'];
        }

        \Log::error('Zoom OAuth Error: ' . $response->body());
        throw new \Exception('Failed to get Zoom access token: ' . $response->json('reason', 'Unknown error'));
    }

    /**
     * Generate a random password for the meeting
     */
    private function generatePassword(): string
    {
        return str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    /**
     * Handle Zoom webhooks
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        try {
            $payload = $request->all();
            $event = $payload['event'] ?? null;

            Log::info('Zoom Webhook Received: ' . $event, $payload);

            switch ($event) {
                case 'meeting.started':
                    $this->handleMeetingStarted($payload);
                    break;
                case 'meeting.ended':
                    $this->handleMeetingEnded($payload);
                    break;
                case 'meeting.participant.joined':
                    $this->handleParticipantJoined($payload);
                    break;
                case 'meeting.participant.left':
                    $this->handleParticipantLeft($payload);
                    break;
                case 'meeting.recording.completed':
                    $this->handleRecordingCompleted($payload);
                    break;
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Zoom Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    private function handleMeetingStarted($payload): void
    {
        $meetingId = $payload['payload']['object']['id'] ?? null;
        if ($meetingId) {
            OnlineClass::where('meeting_id', $meetingId)->update([
                'status' => 'ongoing',
                'started_at' => now(),
            ]);
        }
    }

    private function handleMeetingEnded($payload): void
    {
        $meetingId = $payload['payload']['object']['id'] ?? null;
        if ($meetingId) {
            OnlineClass::where('meeting_id', $meetingId)->update([
                'status' => 'completed',
                'ended_at' => now(),
            ]);
        }
    }

    private function handleParticipantJoined($payload): void
    {
        // Handle participant joined event
        Log::info('Participant joined meeting', $payload);
    }

    private function handleParticipantLeft($payload): void
    {
        // Handle participant left event
        Log::info('Participant left meeting', $payload);
    }

    private function handleRecordingCompleted($payload): void
    {
        // Handle recording completed event
        Log::info('Recording completed', $payload);
    }
}
