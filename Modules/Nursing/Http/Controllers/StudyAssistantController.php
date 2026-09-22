<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class StudyAssistantController extends Controller
{
    public function index()
    {
        return Inertia::render('Nursing/Study/Assistant');
    }

    public function ask(Request $request)
    {
        $request->validate([
            'question' => 'required|string|min:3|max:1000',
        ]);

        $question = $request->question;

        $apiKey = config('services.openai.api_key') ?? config('services.gemini.api_key');

        if (!$apiKey) {
            return response()->json([
                'answer' => 'The AI Study Assistant is not configured. Please contact your administrator to set up the API key.',
                'source' => 'system',
            ]);
        }

        try {
            $systemPrompt = "You are a nursing education study assistant. You help nursing students understand concepts, review procedures, and prepare for clinical practice. You provide educational information only. You NEVER diagnose patients, prescribe medications, or recommend treatment for real patients. You ALWAYS recommend following institutional protocols and professional supervision. Your responses are for educational purposes only.";

            if (config('services.openai.api_key')) {
                $response = Http::withToken(config('services.openai.api_key'))
                    ->timeout(30)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            ['role' => 'system', 'content' => $systemPrompt],
                            ['role' => 'user', 'content' => $question],
                        ],
                        'max_tokens' => 1000,
                        'temperature' => 0.7,
                    ]);

                $answer = $response->json('choices.0.message.content', 'Unable to generate response.');
            } else {
                $answer = 'AI Study Assistant is currently unavailable. Please try again later.';
            }

            return response()->json([
                'answer' => $answer,
                'source' => 'ai',
                'disclaimer' => 'This is an AI-generated educational response. Always verify with authoritative sources and follow institutional protocols.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'answer' => 'An error occurred while processing your request. Please try again.',
                'source' => 'error',
            ], 500);
        }
    }
}
