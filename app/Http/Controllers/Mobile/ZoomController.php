<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ZoomController extends Controller
{
    use ApiResponse;

    // Zoom configuration from your production .env
    private $config = [
        'account_id' => 'fwPKxEo6Sc6_izk51MZD9w',
        'client_id' => 'rhSMKeyBQ_O4_xMVAasPtg',
        'client_secret' => 'Gie3eQqcuwa6TqibRfJ85foX1Pe2MLKQ',
        'environment' => 'production',
    ];

    public function getMeetings(Request $request)
    {
        try {
            $user = $request->user();
            
            // Get meetings for the user's school
            $meetings = \App\Models\ZoomMeeting::where('school_id', $user->school_id)
                ->where('status', '!=', 'cancelled')
                ->orderBy('start_time', 'asc')
                ->get();

            $formattedMeetings = $meetings->map(function($meeting) {
                return [
                    'id' => $meeting->id,
                    'topic' => $meeting->topic,
                    'start_time' => $meeting->start_time->toISOString(),
                    'duration' => $meeting->duration,
                    'join_url' => $meeting->join_url,
                    'password' => $meeting->password,
                    'status' => $this->getMeetingStatus($meeting),
                    'host_id' => $meeting->host_id,
                    'host_name' => $meeting->host_name,
                    'description' => $meeting->description,
                ];
            });

            return $this->successResponse($formattedMeetings, 'Meetings retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error getting Zoom meetings: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve meetings.', 500);
        }
    }

    public function createMeeting(Request $request)
    {
        try {
            $user = $request->user();
            
            $request->validate([
                'topic' => 'required|string|max:255',
                'start_time' => 'required|date|after:now',
                'duration' => 'required|integer|min:1|max:1440',
                'password' => 'nullable|string|max:10',
                'description' => 'nullable|string|max:2000',
            ]);

            // Get Zoom access token
            $accessToken = $this->getZoomAccessToken();

            // Prepare meeting data
            $meetingData = [
                'topic' => $request->input('topic'),
                'type' => 2, // Scheduled meeting
                'start_time' => $request->input('start_time'),
                'duration' => $request->input('duration'),
                'password' => $request->input('password', $this->generatePassword()),
                'agenda' => $request->input('description'),
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'join_before_host' => false,
                    'mute_upon_entry' => true,
                    'waiting_room' => true,
                    'auto_recording' => 'cloud',
                ],
            ];

            // Create meeting via Zoom API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post('https://api.zoom.us/v2/users/me/meetings', $meetingData);

            if ($response->successful()) {
                $zoomMeeting = $response->json();

                // Store meeting in database
                $meeting = \App\Models\ZoomMeeting::create([
                    'zoom_meeting_id' => $zoomMeeting['id'],
                    'topic' => $zoomMeeting['topic'],
                    'start_time' => $zoomMeeting['start_time'],
                    'duration' => $zoomMeeting['duration'],
                    'join_url' => $zoomMeeting['join_url'],
                    'password' => $zoomMeeting['password'],
                    'host_id' => $user->id,
                    'host_name' => $user->name,
                    'description' => $request->input('description'),
                    'status' => 'scheduled',
                    'school_id' => $user->school_id,
                ]);

                // Trigger real-time update
                broadcast(new \App\Events\MeetingCreated($meeting))->toOthers();

                return $this->successResponse([
                    'id' => $meeting->id,
                    'topic' => $meeting->topic,
                    'start_time' => $meeting->start_time->toISOString(),
                    'duration' => $meeting->duration,
                    'join_url' => $meeting->join_url,
                    'password' => $meeting->password,
                    'status' => $meeting->status,
                    'host_id' => $meeting->host_id,
                    'host_name' => $meeting->host_name,
                ], 'Meeting created successfully');
            } else {
                Log::error('Zoom meeting creation failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return $this->errorResponse('Failed to create meeting.', 500);
            }
        } catch (\Exception $e) {
            Log::error('Zoom meeting creation error: ' . $e->getMessage());
            return $this->errorResponse('Failed to create meeting.', 500);
        }
    }

    public function joinMeeting(Request $request, $meetingId)
    {
        try {
            $user = $request->user();
            
            $meeting = \App\Models\ZoomMeeting::where('id', $meetingId)
                ->where('school_id', $user->school_id)
                ->first();

            if (!$meeting) {
                return $this->errorResponse('Meeting not found.', 404);
            }

            // Check if meeting has started
            $now = now();
            if ($meeting->start_time > $now) {
                return $this->errorResponse('Meeting has not started yet.', 400);
            }

            // Check if meeting has ended
            $endTime = $meeting->start_time->addMinutes($meeting->duration);
            if ($now > $endTime) {
                return $this->errorResponse('Meeting has ended.', 400);
            }

            return $this->successResponse([
                'join_url' => $meeting->join_url,
                'password' => $meeting->password,
                'meeting_id' => $meeting->zoom_meeting_id,
            ], 'Meeting join details retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Zoom meeting join error: ' . $e->getMessage());
            return $this->errorResponse('Failed to join meeting.', 500);
        }
    }

    public function getMeetingDetails(Request $request, $meetingId)
    {
        try {
            $user = $request->user();
            
            $meeting = \App\Models\ZoomMeeting::where('id', $meetingId)
                ->where('school_id', $user->school_id)
                ->first();

            if (!$meeting) {
                return $this->errorResponse('Meeting not found.', 404);
            }

            return $this->successResponse([
                'id' => $meeting->id,
                'topic' => $meeting->topic,
                'start_time' => $meeting->start_time->toISOString(),
                'duration' => $meeting->duration,
                'join_url' => $meeting->join_url,
                'password' => $meeting->password,
                'status' => $this->getMeetingStatus($meeting),
                'host_id' => $meeting->host_id,
                'host_name' => $meeting->host_name,
                'description' => $meeting->description,
            ], 'Meeting details retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Zoom meeting details error: ' . $e->getMessage());
            return $this->errorResponse('Failed to get meeting details.', 500);
        }
    }

    public function endMeeting(Request $request, $meetingId)
    {
        try {
            $user = $request->user();
            
            $meeting = \App\Models\ZoomMeeting::where('id', $meetingId)
                ->where('host_id', $user->id)
                ->first();

            if (!$meeting) {
                return $this->errorResponse('Meeting not found or you are not the host.', 404);
            }

            // Update meeting status
            $meeting->update(['status' => 'ended']);

            // Trigger real-time update
            broadcast(new \App\Events\MeetingEnded($meeting))->toOthers();

            return $this->successResponse(null, 'Meeting ended successfully');
        } catch (\Exception $e) {
            Log::error('Zoom meeting end error: ' . $e->getMessage());
            return $this->errorResponse('Failed to end meeting.', 500);
        }
    }

    private function getZoomAccessToken()
    {
        try {
            $response = Http::asForm()->post('https://zoom.us/oauth/token', [
                'grant_type' => 'account_credentials',
                'account_id' => $this->config['account_id'],
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'];
            } else {
                throw new \Exception('Failed to get Zoom access token');
            }
        } catch (\Exception $e) {
            Log::error('Zoom access token error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generatePassword()
    {
        return strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6));
    }

    private function getMeetingStatus($meeting)
    {
        $now = now();
        $startTime = $meeting->start_time;
        $endTime = $startTime->copy()->addMinutes($meeting->duration);

        if ($now < $startTime) {
            return 'scheduled';
        } elseif ($now >= $startTime && $now <= $endTime) {
            return 'live';
        } else {
            return 'ended';
        }
    }
}
