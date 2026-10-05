<?php

namespace Modules\Examination\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Examination\Models\Question;
use Modules\Examination\Models\QuestionCategory;

class AIQuestionGenerator
{
    private $openaiApiKey;
    private $openaiBaseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
    }

    public function generateQuestions($prompt, $options = [])
    {
        $defaultOptions = [
            'count' => 5,
            'difficulty' => 'medium',
            'question_types' => ['mcq', 'true_false', 'short_answer'],
            'subject' => 'General',
            'grade_level' => 'High School',
            'language' => 'English'
        ];

        $options = array_merge($defaultOptions, $options);

        try {
            $systemPrompt = $this->buildSystemPrompt($options);
            $userPrompt = $this->buildUserPrompt($prompt, $options);

            $response = $this->callOpenAI($systemPrompt, $userPrompt);

            if ($response['success']) {
                return $this->parseGeneratedQuestions($response['content'], $options);
            }

            return [
                'success' => false,
                'message' => $response['message'] ?? 'Failed to generate questions'
            ];

        } catch (\Exception $e) {
            Log::error('AI Question Generation Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'AI service temporarily unavailable'
            ];
        }
    }

    private function buildSystemPrompt($options)
    {
        return "You are an expert educational content creator specializing in generating high-quality exam questions. 

Your task is to create {$options['count']} diverse, well-structured questions based on the provided topic and requirements.

QUESTION TYPES TO GENERATE:
- Multiple Choice Questions (MCQ): 4 options, 1 correct answer
- True/False Questions: Clear statements that are definitively true or false
- Short Answer Questions: Require brief, specific responses
- Essay Questions: Require detailed, analytical responses
- Fill-in-the-Blank Questions: Missing key terms or concepts

DIFFICULTY LEVELS:
- Easy: Basic recall and understanding
- Medium: Application and analysis
- Hard: Synthesis, evaluation, and complex problem-solving

REQUIREMENTS:
- Questions must be clear, unambiguous, and educationally sound
- Avoid trick questions or overly complex wording
- Include appropriate explanations for correct answers
- Ensure questions test genuine understanding, not memorization
- Use appropriate academic language for {$options['grade_level']} level
- Generate questions in {$options['language']}

OUTPUT FORMAT:
Return questions in JSON format with the following structure:
{
  \"questions\": [
    {
      \"type\": \"mcq\",
      \"question_text\": \"Question text here\",
      \"options\": [\"Option A\", \"Option B\", \"Option C\", \"Option D\"],
      \"correct_answers\": [\"Option A\"],
      \"explanation\": \"Explanation of correct answer\",
      \"difficulty\": \"medium\",
      \"marks\": 1,
      \"tags\": [\"tag1\", \"tag2\"]
    }
  ]
}";
    }

    private function buildUserPrompt($prompt, $options)
    {
        $questionTypes = implode(', ', $options['question_types']);
        
        return "Generate {$options['count']} exam questions for the following topic:

TOPIC: {$prompt}

SUBJECT: {$options['subject']}
GRADE LEVEL: {$options['grade_level']}
DIFFICULTY: {$options['difficulty']}
QUESTION TYPES: {$questionTypes}

Please create a diverse mix of question types that comprehensively cover the topic. Ensure questions are:
1. Educationally valuable and test real understanding
2. Appropriate for the specified grade level
3. Clear and unambiguous in wording
4. Include proper explanations for answers
5. Cover different aspects of the topic

Generate the questions now:";
    }

    private function callOpenAI($systemPrompt, $userPrompt)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openaiApiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($this->openaiBaseUrl . '/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 4000
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';
                
                return [
                    'success' => true,
                    'content' => $content
                ];
            }

            return [
                'success' => false,
                'message' => 'OpenAI API request failed: ' . $response->body()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'API call failed: ' . $e->getMessage()
            ];
        }
    }

    private function parseGeneratedQuestions($content, $options)
    {
        try {
            // Extract JSON from the response
            $jsonStart = strpos($content, '{');
            $jsonEnd = strrpos($content, '}') + 1;
            
            if ($jsonStart === false || $jsonEnd === false) {
                throw new \Exception('No valid JSON found in response');
            }

            $jsonContent = substr($content, $jsonStart, $jsonEnd - $jsonStart);
            $data = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON format: ' . json_last_error_msg());
            }

            if (!isset($data['questions']) || !is_array($data['questions'])) {
                throw new \Exception('Invalid question format in response');
            }

            $questions = [];
            foreach ($data['questions'] as $questionData) {
                $question = $this->validateAndFormatQuestion($questionData, $options);
                if ($question) {
                    $questions[] = $question;
                }
            }

            return [
                'success' => true,
                'questions' => $questions,
                'count' => count($questions)
            ];

        } catch (\Exception $e) {
            Log::error('Question parsing error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to parse generated questions: ' . $e->getMessage()
            ];
        }
    }

    private function validateAndFormatQuestion($questionData, $options)
    {
        $requiredFields = ['type', 'question_text', 'correct_answers'];
        
        foreach ($requiredFields as $field) {
            if (!isset($questionData[$field])) {
                return null;
            }
        }

        // Validate question type
        $validTypes = ['mcq', 'true_false', 'essay', 'short_answer', 'fill_blank'];
        if (!in_array($questionData['type'], $validTypes)) {
            return null;
        }

        // Format the question
        $formattedQuestion = [
            'question_text' => trim($questionData['question_text']),
            'type' => $questionData['type'],
            'difficulty' => $questionData['difficulty'] ?? $options['difficulty'],
            'marks' => $questionData['marks'] ?? $this->getDefaultMarks($questionData['type']),
            'explanation' => $questionData['explanation'] ?? '',
            'tags' => $questionData['tags'] ?? [$options['subject']],
            'is_active' => true
        ];

        // Add type-specific fields
        switch ($questionData['type']) {
            case 'mcq':
                if (isset($questionData['options']) && is_array($questionData['options'])) {
                    $formattedQuestion['options'] = $questionData['options'];
                } else {
                    return null;
                }
                break;

            case 'true_false':
                $formattedQuestion['options'] = ['True', 'False'];
                break;

            case 'fill_blank':
                // For fill-in-the-blank, correct_answers should be an array
                if (!is_array($questionData['correct_answers'])) {
                    $formattedQuestion['correct_answers'] = [$questionData['correct_answers']];
                }
                break;
        }

        // Ensure correct_answers is an array
        if (!is_array($questionData['correct_answers'])) {
            $formattedQuestion['correct_answers'] = [$questionData['correct_answers']];
        } else {
            $formattedQuestion['correct_answers'] = $questionData['correct_answers'];
        }

        return $formattedQuestion;
    }

    private function getDefaultMarks($type)
    {
        $marksMap = [
            'mcq' => 1,
            'true_false' => 1,
            'short_answer' => 2,
            'fill_blank' => 1,
            'essay' => 5
        ];

        return $marksMap[$type] ?? 1;
    }

    public function generateFromSyllabus($syllabus, $options = [])
    {
        $prompt = "Based on the following syllabus content, generate comprehensive exam questions:\n\n" . $syllabus;
        
        return $this->generateQuestions($prompt, $options);
    }

    public function generateFromNotes($notes, $options = [])
    {
        $prompt = "Based on the following study notes, create exam questions that test understanding:\n\n" . $notes;
        
        return $this->generateQuestions($prompt, $options);
    }

    public function generateAdaptiveQuestions($studentPerformance, $topic, $options = [])
    {
        $difficulty = $this->calculateAdaptiveDifficulty($studentPerformance);
        $options['difficulty'] = $difficulty;
        
        $prompt = "Generate questions for '{$topic}' at {$difficulty} difficulty level, considering the student's performance pattern: " . json_encode($studentPerformance);
        
        return $this->generateQuestions($prompt, $options);
    }

    private function calculateAdaptiveDifficulty($performance)
    {
        $averageScore = $performance['average_score'] ?? 50;
        
        if ($averageScore >= 80) {
            return 'hard';
        } elseif ($averageScore >= 60) {
            return 'medium';
        } else {
            return 'easy';
        }
    }

    public function enhanceExistingQuestion($questionId, $enhancementType = 'improve_clarity')
    {
        $question = Question::findOrFail($questionId);
        
        $prompt = "Improve the following exam question for better clarity and educational value:\n\n";
        $prompt .= "Question: " . $question->question_text . "\n";
        $prompt .= "Type: " . $question->type . "\n";
        $prompt .= "Current Options: " . json_encode($question->options) . "\n";
        $prompt .= "Enhancement Type: " . $enhancementType . "\n\n";
        $prompt .= "Please provide an improved version with better wording, clearer options, and enhanced educational value.";

        return $this->generateQuestions($prompt, ['count' => 1]);
    }
}
