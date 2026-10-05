<?php

namespace Modules\Examination\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Examination\Models\ExamAttempt;
use Modules\Examination\Models\ProctoringLog;
use Carbon\Carbon;

class AICheatingDetection
{
    private $openaiApiKey;
    private $openaiBaseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
    }

    public function analyzeProctoringData($attemptId)
    {
        $attempt = ExamAttempt::with('proctoringLogs')->findOrFail($attemptId);
        
        $proctoringData = $this->collectProctoringData($attempt);
        $riskScore = $this->calculateRiskScore($proctoringData);
        $suspiciousPatterns = $this->detectSuspiciousPatterns($proctoringData);
        $recommendations = $this->generateRecommendations($riskScore, $suspiciousPatterns);

        return [
            'attempt_id' => $attemptId,
            'risk_score' => $riskScore,
            'risk_level' => $this->getRiskLevel($riskScore),
            'suspicious_patterns' => $suspiciousPatterns,
            'recommendations' => $recommendations,
            'analysis_timestamp' => now()->toISOString()
        ];
    }

    private function collectProctoringData($attempt)
    {
        $logs = $attempt->proctoringLogs()
            ->where('created_at', '>=', $attempt->started_at)
            ->orderBy('created_at')
            ->get();

        $data = [
            'total_events' => $logs->count(),
            'violation_count' => $logs->whereIn('severity', ['high', 'critical'])->count(),
            'event_timeline' => $logs->map(function($log) {
                return [
                    'timestamp' => $log->created_at->toISOString(),
                    'type' => $log->event_type,
                    'severity' => $log->severity,
                    'description' => $log->description
                ];
            })->toArray(),
            'time_spent' => $attempt->time_taken_minutes ?? 0,
            'device_info' => $attempt->device_info ?? [],
            'proctoring_data' => $attempt->proctoring_data ?? []
        ];

        return $data;
    }

    private function calculateRiskScore($data)
    {
        $score = 0;
        $weights = [
            'tab_switches' => 15,
            'copy_paste_actions' => 20,
            'dev_tools_usage' => 25,
            'multiple_faces' => 10,
            'voice_detected' => 5,
            'window_focus_loss' => 10,
            'right_click_attempts' => 8,
            'keyboard_shortcuts' => 12,
            'suspicious_timing' => 15
        ];

        // Count specific violations
        $violations = collect($data['event_timeline'])->groupBy('type');
        
        $score += ($violations->get('tab_switch', collect())->count() * $weights['tab_switches']);
        $score += ($violations->get('copy_action', collect())->count() * $weights['copy_paste_actions']);
        $score += ($violations->get('paste_action', collect())->count() * $weights['copy_paste_actions']);
        $score += ($violations->get('dev_tools', collect())->count() * $weights['dev_tools_usage']);
        $score += ($violations->get('multiple_faces', collect())->count() * $weights['multiple_faces']);
        $score += ($violations->get('voice_detected', collect())->count() * $weights['voice_detected']);
        $score += ($violations->get('window_blur', collect())->count() * $weights['window_focus_loss']);
        $score += ($violations->get('right_click', collect())->count() * $weights['right_click_attempts']);
        $score += ($violations->get('keyboard_shortcut', collect())->count() * $weights['keyboard_shortcuts']);

        // Analyze timing patterns
        $timingScore = $this->analyzeTimingPatterns($data);
        $score += $timingScore * $weights['suspicious_timing'];

        // Normalize score to 0-100
        return min(100, max(0, $score));
    }

    private function analyzeTimingPatterns($data)
    {
        $events = collect($data['event_timeline']);
        $suspiciousTiming = 0;

        // Check for rapid-fire violations (multiple violations in short time)
        $recentEvents = $events->where('timestamp', '>=', now()->subMinutes(5)->toISOString());
        if ($recentEvents->count() > 5) {
            $suspiciousTiming += 20;
        }

        // Check for violations clustered around specific times
        $violationTimes = $events->whereIn('severity', ['high', 'critical'])
            ->pluck('timestamp')
            ->map(function($timestamp) {
                return Carbon::parse($timestamp)->minute;
            });

        $timeClusters = $violationTimes->countBy();
        $maxCluster = $timeClusters->max();
        
        if ($maxCluster > 3) {
            $suspiciousTiming += 15;
        }

        // Check for unusual patterns in violation types
        $violationTypes = $events->whereIn('severity', ['high', 'critical'])->pluck('type');
        $uniqueTypes = $violationTypes->unique()->count();
        
        if ($uniqueTypes > 4) {
            $suspiciousTiming += 10; // Multiple different types of violations
        }

        return $suspiciousTiming;
    }

    private function detectSuspiciousPatterns($data)
    {
        $patterns = [];
        $events = collect($data['event_timeline']);

        // Pattern 1: Rapid violation sequence
        $recentEvents = $events->where('timestamp', '>=', now()->subMinutes(2)->toISOString());
        if ($recentEvents->count() >= 3) {
            $patterns[] = [
                'type' => 'rapid_violations',
                'description' => 'Multiple violations detected within 2 minutes',
                'severity' => 'high',
                'count' => $recentEvents->count()
            ];
        }

        // Pattern 2: Repeated tab switching
        $tabSwitches = $events->where('type', 'tab_switch');
        if ($tabSwitches->count() >= 5) {
            $patterns[] = [
                'type' => 'excessive_tab_switching',
                'description' => 'Excessive tab switching detected',
                'severity' => 'medium',
                'count' => $tabSwitches->count()
            ];
        }

        // Pattern 3: Copy-paste activity
        $copyPaste = $events->whereIn('type', ['copy_action', 'paste_action']);
        if ($copyPaste->count() > 0) {
            $patterns[] = [
                'type' => 'copy_paste_activity',
                'description' => 'Copy-paste actions detected during exam',
                'severity' => 'high',
                'count' => $copyPaste->count()
            ];
        }

        // Pattern 4: DevTools usage
        $devTools = $events->where('type', 'dev_tools');
        if ($devTools->count() > 0) {
            $patterns[] = [
                'type' => 'dev_tools_usage',
                'description' => 'Browser developer tools accessed during exam',
                'severity' => 'critical',
                'count' => $devTools->count()
            ];
        }

        // Pattern 5: Multiple faces detected
        $multipleFaces = $events->where('type', 'multiple_faces');
        if ($multipleFaces->count() > 0) {
            $patterns[] = [
                'type' => 'multiple_faces',
                'description' => 'Multiple faces detected in webcam feed',
                'severity' => 'high',
                'count' => $multipleFaces->count()
            ];
        }

        // Pattern 6: Voice activity
        $voiceActivity = $events->where('type', 'voice_detected');
        if ($voiceActivity->count() >= 3) {
            $patterns[] = [
                'type' => 'excessive_voice_activity',
                'description' => 'Excessive voice activity detected',
                'severity' => 'medium',
                'count' => $voiceActivity->count()
            ];
        }

        return $patterns;
    }

    private function generateRecommendations($riskScore, $patterns)
    {
        $recommendations = [];

        if ($riskScore >= 80) {
            $recommendations[] = [
                'type' => 'immediate_action',
                'priority' => 'critical',
                'action' => 'Immediately review exam attempt and consider disqualification',
                'reason' => 'Very high risk score indicates potential cheating'
            ];
        } elseif ($riskScore >= 60) {
            $recommendations[] = [
                'type' => 'review_required',
                'priority' => 'high',
                'action' => 'Review exam attempt and proctoring logs in detail',
                'reason' => 'High risk score requires manual review'
            ];
        } elseif ($riskScore >= 40) {
            $recommendations[] = [
                'type' => 'monitor',
                'priority' => 'medium',
                'action' => 'Continue monitoring and review if additional violations occur',
                'reason' => 'Moderate risk score indicates need for continued monitoring'
            ];
        }

        // Pattern-specific recommendations
        foreach ($patterns as $pattern) {
            switch ($pattern['type']) {
                case 'dev_tools_usage':
                    $recommendations[] = [
                        'type' => 'immediate_action',
                        'priority' => 'critical',
                        'action' => 'Disqualify exam attempt immediately',
                        'reason' => 'Developer tools usage is a clear violation'
                    ];
                    break;

                case 'copy_paste_activity':
                    $recommendations[] = [
                        'type' => 'review_required',
                        'priority' => 'high',
                        'action' => 'Review copied content and determine if cheating occurred',
                        'reason' => 'Copy-paste activity detected during exam'
                    ];
                    break;

                case 'multiple_faces':
                    $recommendations[] = [
                        'type' => 'investigate',
                        'priority' => 'high',
                        'action' => 'Review webcam footage to identify additional persons',
                        'reason' => 'Multiple faces detected in webcam feed'
                    ];
                    break;

                case 'excessive_tab_switching':
                    $recommendations[] = [
                        'type' => 'monitor',
                        'priority' => 'medium',
                        'action' => 'Review tab switching pattern for suspicious activity',
                        'reason' => 'Excessive tab switching may indicate external resource usage'
                    ];
                    break;
            }
        }

        return $recommendations;
    }

    private function getRiskLevel($score)
    {
        if ($score >= 80) return 'critical';
        if ($score >= 60) return 'high';
        if ($score >= 40) return 'medium';
        if ($score >= 20) return 'low';
        return 'minimal';
    }

    public function generateDetailedReport($attemptId)
    {
        $analysis = $this->analyzeProctoringData($attemptId);
        
        // Use AI to generate a detailed narrative report
        $prompt = $this->buildReportPrompt($analysis);
        
        try {
            $response = $this->callOpenAI($prompt);
            
            if ($response['success']) {
                $analysis['ai_generated_report'] = $response['content'];
            }
        } catch (\Exception $e) {
            Log::error('AI Report Generation Error: ' . $e->getMessage());
        }

        return $analysis;
    }

    private function buildReportPrompt($analysis)
    {
        $patterns = collect($analysis['suspicious_patterns'])
            ->map(function($pattern) {
                return "- {$pattern['description']} (Severity: {$pattern['severity']}, Count: {$pattern['count']})";
            })
            ->join("\n");

        $recommendations = collect($analysis['recommendations'])
            ->map(function($rec) {
                return "- {$rec['action']} (Priority: {$rec['priority']})";
            })
            ->join("\n");

        return "Generate a detailed proctoring analysis report for an online exam attempt.

EXAM ATTEMPT DETAILS:
- Risk Score: {$analysis['risk_score']}/100
- Risk Level: {$analysis['risk_level']}
- Total Events: {$analysis['total_events']}
- Violation Count: {$analysis['violation_count']}

SUSPICIOUS PATTERNS DETECTED:
{$patterns}

RECOMMENDATIONS:
{$recommendations}

Please provide a professional, detailed report that:
1. Summarizes the key findings
2. Explains the risk assessment
3. Details the suspicious patterns found
4. Provides clear recommendations for action
5. Uses appropriate academic/professional language
6. Is suitable for sharing with academic administrators

Format the report in clear sections with headings.";
    }

    private function callOpenAI($prompt)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openaiApiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($this->openaiBaseUrl . '/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an expert in academic integrity and online proctoring analysis. Generate professional, detailed reports for exam monitoring systems.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.3,
                'max_tokens' => 2000
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'content' => $data['choices'][0]['message']['content'] ?? ''
                ];
            }

            return [
                'success' => false,
                'message' => 'OpenAI API request failed'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'API call failed: ' . $e->getMessage()
            ];
        }
    }

    public function batchAnalyzeAttempts($attemptIds)
    {
        $results = [];
        
        foreach ($attemptIds as $attemptId) {
            try {
                $results[$attemptId] = $this->analyzeProctoringData($attemptId);
            } catch (\Exception $e) {
                $results[$attemptId] = [
                    'error' => 'Analysis failed: ' . $e->getMessage()
                ];
            }
        }

        return $results;
    }

    public function getCheatingStatistics($dateRange = null)
    {
        $query = ExamAttempt::whereHas('exam', function($q) {
            $q->where('enable_proctoring', true);
        });

        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        $attempts = $query->with('proctoringLogs')->get();
        
        $stats = [
            'total_attempts' => $attempts->count(),
            'high_risk_attempts' => 0,
            'critical_risk_attempts' => 0,
            'average_risk_score' => 0,
            'common_violations' => [],
            'risk_distribution' => [
                'minimal' => 0,
                'low' => 0,
                'medium' => 0,
                'high' => 0,
                'critical' => 0
            ]
        ];

        $totalRiskScore = 0;
        $violationCounts = [];

        foreach ($attempts as $attempt) {
            $analysis = $this->analyzeProctoringData($attempt->id);
            $riskScore = $analysis['risk_score'];
            $riskLevel = $analysis['risk_level'];

            $totalRiskScore += $riskScore;
            $stats['risk_distribution'][$riskLevel]++;

            if ($riskLevel === 'high') {
                $stats['high_risk_attempts']++;
            } elseif ($riskLevel === 'critical') {
                $stats['critical_risk_attempts']++;
            }

            // Count violations
            foreach ($analysis['suspicious_patterns'] as $pattern) {
                $violationCounts[$pattern['type']] = ($violationCounts[$pattern['type']] ?? 0) + 1;
            }
        }

        if ($attempts->count() > 0) {
            $stats['average_risk_score'] = round($totalRiskScore / $attempts->count(), 2);
        }

        arsort($violationCounts);
        $stats['common_violations'] = array_slice($violationCounts, 0, 5, true);

        return $stats;
    }
}
