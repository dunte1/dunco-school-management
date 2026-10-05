<?php

namespace Modules\Finance\Http\Controllers\Reports;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Finance\Models\Invoice;

class AgingController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $invoices = Invoice::with('payments')
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->get();

        $buckets = [
            '0_30' => 0.0,
            '31_60' => 0.0,
            '61_90' => 0.0,
            '90_plus' => 0.0,
        ];

        foreach ($invoices as $invoice) {
            $paid = (float) ($invoice->payments->sum('amount'));
            $outstanding = max(0.0, (float) $invoice->total_amount - $paid);
            if ($outstanding <= 0) {
                continue;
            }
            $days = now()->diffInDays(\Illuminate\Support\Carbon::parse($invoice->due_date), false) * -1;
            if ($days <= 30) {
                $buckets['0_30'] += $outstanding;
            } elseif ($days <= 60) {
                $buckets['31_60'] += $outstanding;
            } elseif ($days <= 90) {
                $buckets['61_90'] += $outstanding;
            } else {
                $buckets['90_plus'] += $outstanding;
            }
        }

        // Top overdue list
        $overdue = $invoices->map(function ($inv) {
            $paid = (float) ($inv->payments->sum('amount'));
            $outstanding = max(0.0, (float) $inv->total_amount - $paid);
            $daysPast = now()->diffInDays(\Illuminate\Support\Carbon::parse($inv->due_date), false) * -1;
            return [
                'id' => $inv->id,
                'student_id' => $inv->student_id,
                'due_date' => $inv->due_date,
                'outstanding' => $outstanding,
                'days_past_due' => $daysPast,
            ];
        })->filter(fn($r) => $r['outstanding'] > 0 && $r['days_past_due'] > 0)
            ->sortByDesc('days_past_due')
            ->take(50)
            ->values();

        if ($request->get('export') === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="aging_report.csv"',
            ];
            $callback = function () use ($buckets, $overdue) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Bucket', 'Amount']);
                fputcsv($handle, ['0-30', number_format($buckets['0_30'], 2)]);
                fputcsv($handle, ['31-60', number_format($buckets['31_60'], 2)]);
                fputcsv($handle, ['61-90', number_format($buckets['61_90'], 2)]);
                fputcsv($handle, ['90+', number_format($buckets['90_plus'], 2)]);
                fputcsv($handle, []);
                fputcsv($handle, ['Top Overdue', '']);
                fputcsv($handle, ['Invoice ID', 'Student ID', 'Due Date', 'Outstanding', 'Days Past Due']);
                foreach ($overdue as $row) {
                    fputcsv($handle, [$row['id'], $row['student_id'], $row['due_date'], number_format($row['outstanding'], 2), $row['days_past_due']]);
                }
                fclose($handle);
            };
            return response()->stream($callback, 200, $headers);
        }

        // Create the $out variable structure that the view expects
        $out = [
            'current' => collect(),
            'over30' => collect(),
            'over60' => collect(),
            'over90' => collect(),
        ];

        // Group invoices by aging buckets for detailed view
        foreach ($invoices as $invoice) {
            $paid = (float) ($invoice->payments->sum('amount'));
            $outstanding = max(0.0, (float) $invoice->total_amount - $paid);
            if ($outstanding <= 0) {
                continue;
            }
            
            $days = now()->diffInDays(\Illuminate\Support\Carbon::parse($invoice->due_date), false) * -1;
            
            // Create invoice object with student relationship
            $invoiceObj = (object) [
                'id' => $invoice->id,
                'student_id' => $invoice->student_id,
                'student' => (object) [
                    'name' => $invoice->student->name ?? 'Unknown Student'
                ],
                'due_date' => $invoice->due_date,
                'total_amount' => $invoice->total_amount,
                'payments' => $invoice->payments
            ];
            
            if ($days <= 30) {
                $out['current']->push($invoiceObj);
            } elseif ($days <= 60) {
                $out['over30']->push($invoiceObj);
            } elseif ($days <= 90) {
                $out['over60']->push($invoiceObj);
            } else {
                $out['over90']->push($invoiceObj);
            }
        }

        return view('finance::reports.aging', compact('buckets', 'overdue', 'out'));
    }
}


