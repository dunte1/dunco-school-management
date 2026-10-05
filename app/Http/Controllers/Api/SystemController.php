<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SystemController extends Controller
{
    public function analytics(Request $request): JsonResponse
    {
        // Lightweight, safe analytics defaults; use real tables if available
        $summary = [
            'schools' => 0,
            'users' => 0,
            'mtd_revenue' => 0.0,
            'failed_payments_30d' => 0,
        ];
        try {
            if (DB::getSchemaBuilder()->hasTable('schools')) {
                $summary['schools'] = (int) DB::table('schools')->count();
            }
            if (DB::getSchemaBuilder()->hasTable('users')) {
                $summary['users'] = (int) DB::table('users')->count();
            }
            if (DB::getSchemaBuilder()->hasTable('payments')) {
                $summary['mtd_revenue'] = (float) DB::table('payments')
                    ->whereIn('status', ['completed','success','paid','confirmed'])
                    ->whereDate('payment_date', '>=', now()->startOfMonth())
                    ->sum('amount');
                $summary['failed_payments_30d'] = (int) DB::table('payments')
                    ->whereIn('status', ['failed','declined','error'])
                    ->whereDate('payment_date', '>=', now()->subDays(30))
                    ->count();
            }
        } catch (\Throwable $e) {
            // keep defaults
        }
        return response()->json([
            'ok' => true,
            'summary' => $summary,
            'generated_at' => now()->toIso8601String(),
        ]);
    }

    public function translations(string $lang = 'en'): JsonResponse
    {
        // Minimal placeholder translation payload to avoid 404
        $lang = $lang ?: 'en';
        $strings = [
            'en' => [
                'dashboard.title' => 'Dashboard',
                'dashboard.loading' => 'Loading...',
                'actions.apply' => 'Apply',
            ],
        ];
        return response()->json([
            'ok' => true,
            'lang' => $lang,
            'strings' => $strings[$lang] ?? $strings['en'],
        ]);
    }

    public function performance(Request $request): JsonResponse
    {
        // Simple metrics: queue backlog, failed jobs 24h, cache health
        $metrics = [
            'queue_backlog' => 0,
            'failed_24h' => 0,
            'cache_ok' => false,
        ];
        try {
            if (DB::getSchemaBuilder()->hasTable('jobs')) {
                $metrics['queue_backlog'] = (int) DB::table('jobs')->count();
            }
            if (DB::getSchemaBuilder()->hasTable('failed_jobs')) {
                $metrics['failed_24h'] = (int) DB::table('failed_jobs')->where('failed_at', '>=', now()->subDay())->count();
            }
            Cache::put('__perf_probe__', 'ok', 60);
            $metrics['cache_ok'] = Cache::get('__perf_probe__') === 'ok';
        } catch (\Throwable $e) {
            // keep defaults
        }
        return response()->json([
            'ok' => true,
            'metrics' => $metrics,
            'generated_at' => now()->toIso8601String(),
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['ok' => true, 'count' => 0]);
        }
        try {
            $count = Auth::user()->unreadNotifications()->count();
        } catch (\Throwable $e) {
            $count = 0;
        }
        return response()->json(['ok' => true, 'count' => $count]);
    }
}
