<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Basic live metrics (safe fallbacks for SQLite)
        $schoolsQ = DB::table('schools');
        $usersQ = DB::table('users');
        $totalSchools = DB::getSchemaBuilder()->hasTable('schools') ? $schoolsQ->count() : 0;
        $activeSchools = 0;
        if (DB::getSchemaBuilder()->hasTable('schools')) {
            try { $activeSchools = (clone $schoolsQ)->where('status', 'active')->count(); } catch (\Throwable $e) { $activeSchools = 0; }
        }
        $totalUsers = DB::getSchemaBuilder()->hasTable('users') ? $usersQ->count() : 0;

        // Placeholder computed KPIs; replace with real queries when available
        // Finance KPIs (real data when available)
        $platformRevenue = 0.0; // MTD revenue
        $failedPayments = 0;
        try {
            if (DB::getSchemaBuilder()->hasTable('payments')) {
                $startOfMonth = now()->startOfMonth();
                // Sum successful payments
                $platformRevenue = (float) DB::table('payments')
                    ->whereDate('payment_date', '>=', $startOfMonth)
                    ->whereIn('status', ['completed','success','paid','confirmed'])
                    ->sum('amount');
                // Count failed payments (last 30 days)
                $failedPayments = (int) DB::table('payments')
                    ->whereDate('payment_date', '>=', now()->subDays(30))
                    ->whereIn('status', ['failed','declined','reversed','expired','cancelled'])
                    ->count();
            }
        } catch (\Throwable $e) {
            // keep defaults if any error
        }

        // System health & sessions (best-effort calculations with fallbacks)
        $systemHealth = 100.0;
        // Cache test
        try {
            Cache::put('__health_probe__', 'ok', 60);
            $probe = Cache::get('__health_probe__');
            $cacheProbeOk = ($probe === 'ok');
            $cacheProbeAt = now();
            if (!$cacheProbeOk) { $systemHealth -= 10; }
        } catch (\Throwable $e) { $systemHealth -= 10; }

        // Failed jobs penalty
        try {
            if (DB::getSchemaBuilder()->hasTable('failed_jobs')) {
                $failed24 = (int) DB::table('failed_jobs')
                    ->where('failed_at', '>=', now()->subDay())
                    ->count();
                $systemHealth -= min(30, $failed24 * 2);
            }
        } catch (\Throwable $e) { /* ignore */ }

        // Queue backlog penalty
        $queueBacklog = 0;
        try {
            if (DB::getSchemaBuilder()->hasTable('jobs')) {
                $queueBacklog = (int) DB::table('jobs')->count();
                $systemHealth -= min(20, intdiv($queueBacklog, 10));
            }
        } catch (\Throwable $e) { /* ignore */ }

        // Disk space check
        try {
            $free = @disk_free_space(base_path());
            $total = @disk_total_space(base_path());
            if ($free !== false && $total) {
                $pct = ($free / $total) * 100.0;
                if ($pct < 10) { $systemHealth -= 20; }
                elseif ($pct < 20) { $systemHealth -= 10; }
            }
        } catch (\Throwable $e) { /* ignore */ }

        $systemHealth = max(0, min(100, round($systemHealth, 1)));

        // Active sessions count (DB sessions or file sessions)
        $activeSessions = 0;
        try {
            if (DB::getSchemaBuilder()->hasTable('sessions')) {
                $activeSessions = (int) DB::table('sessions')
                    ->where('last_activity', '>=', now()->subMinutes(30)->getTimestamp())
                    ->count();
            } else {
                $dir = storage_path('framework/sessions');
                if (is_dir($dir)) {
                    $now = time();
                    $n = 0;
                    foreach (glob($dir . DIRECTORY_SEPARATOR . '*') ?: [] as $f) {
                        if (@filemtime($f) >= ($now - 1800)) { $n++; }
                    }
                    $activeSessions = $n;
                }
            }
        } catch (\Throwable $e) { $activeSessions = 0; }

        // Server load approximation (based on queue backlog and failed jobs)
        $serverLoad = 10; // base
        $failedJobsRecent = 0; $failedJobsList = [];
        try {
            $failedC = 0; $backlogC = 0;
            if (DB::getSchemaBuilder()->hasTable('failed_jobs')) {
                $failedC = (int) DB::table('failed_jobs')->where('failed_at', '>=', now()->subDay())->count();
                $failedJobsRecent = $failedC;
                $failedJobsList = DB::table('failed_jobs')
                    ->select('id','failed_at','exception')
                    ->orderByDesc('failed_at')
                    ->limit(10)
                    ->get();
            }
            if (DB::getSchemaBuilder()->hasTable('jobs')) {
                $backlogC = (int) DB::table('jobs')->count();
            }
            $serverLoad = min(100, 10 + ($backlogC * 2) + ($failedC * 3));
        } catch (\Throwable $e) { /* keep base */ }

        // Last sync minutes: time since latest update across a few key tables
        $lastSyncMinutes = 2;
        try {
            $latest = null;
            foreach (['payments','invoices','schools','users'] as $t) {
                if (DB::getSchemaBuilder()->hasTable($t) && DB::getSchemaBuilder()->hasColumn($t, 'updated_at')) {
                    $ts = DB::table($t)->max('updated_at');
                    if ($ts) {
                        $dt = now()->parse($ts);
                        if (!$latest || $dt->gt($latest)) { $latest = $dt; }
                    }
                }
            }
            if ($latest) { $lastSyncMinutes = max(0, now()->diffInMinutes($latest)); }
        } catch (\Throwable $e) { /* keep default */ }

        // Critical alerts: approvals (HR leaves pending) and tickets (if table exists)
        $approvalsPending = 0; $ticketsOpen = 0;
        try {
            if (DB::getSchemaBuilder()->hasTable('leaves')) {
                $approvalsPending = (int) DB::table('leaves')
                    ->whereIn('status', ['pending','awaiting','submitted'])
                    ->count();
            }
        } catch (\Throwable $e) { $approvalsPending = 0; }

        try {
            if (DB::getSchemaBuilder()->hasTable('tickets')) {
                $ticketsOpen = (int) DB::table('tickets')
                    ->whereIn('status', ['open','pending','in_progress'])
                    ->count();
            } elseif (DB::getSchemaBuilder()->hasTable('helpdesk_tickets')) {
                $ticketsOpen = (int) DB::table('helpdesk_tickets')
                    ->whereIn('status', ['open','pending','in_progress'])
                    ->count();
            }
        } catch (\Throwable $e) { $ticketsOpen = 0; }

        $licensesExpiring = 0;
        try {
            if (DB::getSchemaBuilder()->hasTable('licenses')) {
                $licensesExpiring = (int) DB::table('licenses')
                    ->where('status', 'active')
                    ->whereNotNull('expires_at')
                    ->whereDate('expires_at', '<=', now()->addDays(30))
                    ->count();
            }
        } catch (\Throwable $e) { $licensesExpiring = 0; }

        $critical = [
            'approvals' => $approvalsPending,
            'tickets' => $ticketsOpen,
            'failed_payments' => $failedPayments,
            'licenses_expiring' => $licensesExpiring,
        ];

        // Period filters
        $growthMonths = max(1, min(24, (int) $request->get('growth_months', 12)));
        $revenueMonths = max(2, min(12, (int) $request->get('revenue_months', 4)));

        // Growth trends: new schools by month (configurable months) + active_rate & retention (approx.)
        $growth = [ 'labels' => [], 'new_schools' => [], 'active_rate' => [], 'retention' => [] ];
        try {
            if (DB::getSchemaBuilder()->hasTable('schools')) {
                $start = now()->startOfMonth()->subMonths($growthMonths - 1);
                $rows = DB::table('schools')
                    ->selectRaw("strftime('%Y-%m', created_at) as ym, COUNT(*) as c")
                    ->whereDate('created_at', '>=', $start)
                    ->groupBy('ym')
                    ->orderBy('ym')
                    ->get();
                $map = collect($rows)->keyBy('ym');
                for ($i = 0; $i < $growthMonths; $i++) {
                    $ym = $start->copy()->addMonths($i)->format('Y-m');
                    $growth['labels'][] = $start->copy()->addMonths($i)->format('M');
                    $growth['new_schools'][] = (int)($map[$ym]->c ?? 0);
                    // Active rate approximation: active schools up to end of month / total schools up to end of month
                    $endOfMonth = $start->copy()->addMonths($i)->endOfMonth();
                    $totalToDate = (int) DB::table('schools')->whereDate('created_at', '<=', $endOfMonth)->count();
                    $activeToDate = 0;
                    try {
                        $activeToDate = (int) DB::table('schools')->whereDate('created_at', '<=', $endOfMonth)->where('status','active')->count();
                    } catch (\Throwable $e) { $activeToDate = 0; }
                    $rate = $totalToDate > 0 ? round(($activeToDate / $totalToDate) * 100, 1) : 0.0;
                    $growth['active_rate'][] = $rate;
                    // Retention proxy: same as active_rate until churn/renewal data is available
                    $growth['retention'][] = $rate;
                }
            }
        } catch (\Throwable $e) {
            // keep empty
        }

        // Revenue breakdown (prefer fee types via invoices/items if present; fallback to payment methods)
        // Monthly totals (configurable months) and monthly category split (top 3 categories)
        $revenue = [ 'breakdown' => [], 'mom' => 0.0, 'monthly' => [], 'categories' => [], 'monthly_split' => [], 'label' => 'Payment Methods' ];
        try {
            if (DB::getSchemaBuilder()->hasTable('payments')) {
                $startOfMonth = now()->startOfMonth();
                // Default: breakdown by payment method
                $breakdownRows = DB::table('payments')
                    ->select('method', DB::raw('SUM(amount) as total'))
                    ->whereDate('payment_date', '>=', $startOfMonth)
                    ->whereIn('status', ['completed','success','paid','confirmed'])
                    ->groupBy('method')
                    ->orderByDesc('total')
                    ->get();
                $revenue['breakdown'] = $breakdownRows->map(function($r){ return ['label' => $r->method ?? 'unknown', 'value' => (float)$r->total]; })->values()->all();
                $revenue['label'] = 'Payment Methods';

                // If Finance metadata exists, compute invoice-item breakdown by fee types (invoiced amount)
                if (DB::getSchemaBuilder()->hasTable('invoice_items') && DB::getSchemaBuilder()->hasTable('invoices') &&
                    (DB::getSchemaBuilder()->hasTable('fee_types') || DB::getSchemaBuilder()->hasTable('fees'))) {
                    $itemQuery = DB::table('invoice_items')
                        ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                        ->leftJoin('fees', 'invoice_items.fee_id', '=', 'fees.id')
                        ->leftJoin('fee_types', 'fees.fee_type_id', '=', 'fee_types.id')
                        ->whereDate('invoices.created_at', '>=', $startOfMonth)
                        ->select(DB::raw("COALESCE(fee_types.name, fees.name, 'Other') as category"), DB::raw('SUM(invoice_items.amount) as total'))
                        ->groupBy('category')
                        ->orderByDesc('total')
                        ->get();
                    if ($itemQuery->count() > 0) {
                        $revenue['breakdown'] = $itemQuery->map(function($r){ return ['label' => $r->category ?? 'Other', 'value' => (float)$r->total]; })->values()->all();
                        $revenue['label'] = 'Fee Types (Invoiced)';
                    }
                }

                // Monthly totals for selected window
                $start4 = now()->startOfMonth()->subMonths($revenueMonths - 1);
                $rows4 = DB::table('payments')
                    ->selectRaw("strftime('%Y-%m', payment_date) as ym, SUM(amount) as total")
                    ->whereDate('payment_date', '>=', $start4)
                    ->whereIn('status', ['completed','success','paid','confirmed'])
                    ->groupBy('ym')
                    ->orderBy('ym')
                    ->get();
                $map4 = collect($rows4)->keyBy('ym');
                $prev = null; $curr = null;
                for ($i = 0; $i < $revenueMonths; $i++) {
                    $ym = $start4->copy()->addMonths($i)->format('Y-m');
                    $label = $start4->copy()->addMonths($i)->format('F');
                    $total = (float)($map4[$ym]->total ?? 0);
                    $revenue['monthly'][] = [ 'label' => $label, 'total' => $total ];
                    if ($i === ($revenueMonths - 2)) { $prev = $total; }
                    if ($i === ($revenueMonths - 1)) { $curr = $total; }
                }
                if ($prev && $curr !== null && $prev > 0) {
                    $revenue['mom'] = round((($curr - $prev) / $prev) * 100, 1);
                }

                // Determine top categories across selected window
                $categories = [];
                if (DB::getSchemaBuilder()->hasTable('invoice_items') && DB::getSchemaBuilder()->hasTable('invoices') &&
                    (DB::getSchemaBuilder()->hasTable('fee_types') || DB::getSchemaBuilder()->hasTable('fees'))) {
                    $topByType = DB::table('invoice_items')
                        ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                        ->leftJoin('fees', 'invoice_items.fee_id', '=', 'fees.id')
                        ->leftJoin('fee_types', 'fees.fee_type_id', '=', 'fee_types.id')
                        ->whereDate('invoices.created_at', '>=', $start4)
                        ->select(DB::raw("COALESCE(fee_types.name, fees.name, 'Other') as category"), DB::raw('SUM(invoice_items.amount) as total'))
                        ->groupBy('category')
                        ->orderByDesc('total')
                        ->limit(3)
                        ->get();
                    $categories = $topByType->pluck('category')->map(function($m){ return $m ?: 'Other'; })->values()->all();
                    if (count($categories) > 0) { $revenue['label'] = 'Fee Types (Invoiced)'; }
                }
                if (empty($categories)) {
                    $topMethodsRows = DB::table('payments')
                        ->select('method', DB::raw('SUM(amount) as total'))
                        ->whereDate('payment_date', '>=', $start4)
                        ->whereIn('status', ['completed','success','paid','confirmed'])
                        ->groupBy('method')
                        ->orderByDesc('total')
                        ->limit(3)
                        ->get();
                    $categories = $topMethodsRows->pluck('method')->map(function($m){ return $m ?: 'unknown'; })->values()->all();
                    if (count($categories) > 0) { $revenue['label'] = 'Payment Methods'; }
                }
                // Ensure exactly 3 columns for UI stability
                while (count($categories) < 3) { $categories[] = '—'; }
                $revenue['categories'] = $categories;

                // Build monthly split aligned to categories
                for ($i = 0; $i < $revenueMonths; $i++) {
                    $monthStart = $start4->copy()->addMonths($i);
                    $ym = $monthStart->format('Y-m');
                    $label = $monthStart->format('F');
                    $row = ['label' => $label, 'categories' => [], 'total' => 0.0];
                    $catTotals = [];
                    foreach ($categories as $cat) {
                        if ($cat === '—') { $catTotals[$cat] = 0.0; continue; }
                        $sum = 0.0;
                        if ($revenue['label'] === 'Fee Types (Invoiced)') {
                            $sum = (float) DB::table('invoice_items')
                                ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                                ->leftJoin('fees', 'invoice_items.fee_id', '=', 'fees.id')
                                ->leftJoin('fee_types', 'fees.fee_type_id', '=', 'fee_types.id')
                                ->whereDate('invoices.created_at', '>=', $monthStart->copy()->startOfMonth())
                                ->whereDate('invoices.created_at', '<=', $monthStart->copy()->endOfMonth())
                                ->where(DB::raw("COALESCE(fee_types.name, fees.name, 'Other')"), '=', $cat)
                                ->sum('invoice_items.amount');
                        } else {
                            $sum = (float) DB::table('payments')
                                ->whereDate('payment_date', '>=', $monthStart->copy()->startOfMonth())
                                ->whereDate('payment_date', '<=', $monthStart->copy()->endOfMonth())
                                ->whereIn('status', ['completed','success','paid','confirmed'])
                                ->where(function($q) use ($cat){
                                    if ($cat === 'unknown') { $q->whereNull('method'); }
                                    else { $q->where('method', $cat); }
                                })
                                ->sum('amount');
                        }
                        $catTotals[$cat] = $sum;
                    }
                    $row['categories'] = $catTotals;
                    $row['total'] = array_sum($catTotals);
                    $revenue['monthly_split'][] = $row;
                }
            }
        } catch (\Throwable $e) {
            // keep defaults
        }

        // Recent activities feed: schools created, payments last 7 days, student enrollments last 7 days
        $activities = [];
        try {
            // New schools
            if (DB::getSchemaBuilder()->hasTable('schools')) {
                $rows = DB::table('schools')->whereDate('created_at', '>=', now()->subDays(30))->select('id','name','created_at')->orderByDesc('created_at')->limit(10)->get();
                foreach ($rows as $r) {
                    $activities[] = [
                        'type' => 'school_created',
                        'time' => now()->parse($r->created_at),
                        'label' => 'New School',
                        'detail' => $r->name ?? ('School #'.$r->id),
                    ];
                }
            }
            // Recent payments
            if (DB::getSchemaBuilder()->hasTable('payments')) {
                $rows = DB::table('payments')->whereDate('payment_date', '>=', now()->subDays(7))->select('id','amount','payment_date','method','status')->orderByDesc('payment_date')->limit(10)->get();
                foreach ($rows as $r) {
                    $activities[] = [
                        'type' => 'payment',
                        'time' => now()->parse($r->payment_date),
                        'label' => 'Payment',
                        'detail' => '$'.number_format((float)$r->amount,2).' via '.($r->method ?? 'unknown').' ('.($r->status ?? 'n/a').')',
                    ];
                }
            }
            // Student enrollments
            if (DB::getSchemaBuilder()->hasTable('academic_students')) {
                $rows = DB::table('academic_students')->whereDate('created_at', '>=', now()->subDays(7))->select('id','name','created_at','school_id')->orderByDesc('created_at')->limit(10)->get();
                foreach ($rows as $r) {
                    $activities[] = [
                        'type' => 'enrollment',
                        'time' => now()->parse($r->created_at),
                        'label' => 'New Enrollment',
                        'detail' => $r->name ?? ('Student #'.$r->id),
                    ];
                }
            }
            // Sort by time desc and take top 10
            usort($activities, function($a,$b){ return $b['time']->timestamp <=> $a['time']->timestamp; });
            $activities = array_slice($activities, 0, 10);
        } catch (\Throwable $e) {
            $activities = [];
        }

        // Recent schools with student/staff counts
        $recentSchools = [];
        try {
            if (DB::getSchemaBuilder()->hasTable('schools')) {
                $schools = DB::table('schools')->orderByDesc('id')->limit(5)->get();
                foreach ($schools as $s) {
                    $students = 0; $staff = 0;
                    if (DB::getSchemaBuilder()->hasTable('academic_students')) {
                        $students = (int) DB::table('academic_students')->where('school_id', $s->id)->count();
                    }
                    if (DB::getSchemaBuilder()->hasTable('staff')) {
                        $staff = (int) DB::table('staff')->where('school_id', $s->id)->count();
                    }
                    $recentSchools[] = [
                        'name' => $s->name ?? ('School #'.$s->id),
                        'students' => $students,
                        'staff' => $staff,
                        'status' => $s->status ?? null,
                    ];
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return view('super_admin.dashboard', compact(
            'totalSchools','activeSchools','totalUsers','platformRevenue','systemHealth','activeSessions','serverLoad','lastSyncMinutes','critical','growth','revenue','recentSchools','queueBacklog','failedJobsRecent','failedJobsList','cacheProbeOk','cacheProbeAt','activities'
        ));
    }
}
