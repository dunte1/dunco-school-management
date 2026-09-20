<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Academic\Models\Student;
use Modules\HR\Models\Staff;
use Modules\Finance\Models\Fee;
use Modules\Finance\Models\Invoice;
use Modules\Finance\Models\Payment;
use App\Models\Modules\Library\Models\Book;

class GlobalSearchController extends Controller
{
    /**
     * Search across students, staff, books, fees, invoices, and payments.
     *
     * GET /api/v1/mobile/search?q=keyword&type=students|staff|books|fees|all
     */
    public function search(Request $request): JsonResponse
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'q'     => 'required|string|min:2|max:100',
            'type'  => 'nullable|string|in:students,staff,books,fees,invoices,payments,all',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $query  = $request->input('q');
        $type   = $request->input('type', 'all');
        $limit  = $request->input('limit', 10);

        try {
            $results = [];

            if (in_array($type, ['all', 'students'], true)) {
                $results['students'] = $this->searchStudents($query, $limit);
            }

            if (in_array($type, ['all', 'staff'], true)) {
                $results['staff'] = $this->searchStaff($query, $limit);
            }

            if (in_array($type, ['all', 'books'], true)) {
                $results['books'] = $this->searchBooks($query, $limit);
            }

            if (in_array($type, ['all', 'fees'], true)) {
                $results['fees'] = $this->searchFees($query, $limit);
            }

            if (in_array($type, ['all', 'invoices'], true)) {
                $results['invoices'] = $this->searchInvoices($query, $limit);
            }

            if (in_array($type, ['all', 'payments'], true)) {
                $results['payments'] = $this->searchPayments($query, $limit);
            }

            // Flatten total count
            $totalCount = collect($results)->sum(function ($items) {
                return $items instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
                    ? $items->total()
                    : count($items);
            });

            return response()->json([
                'success' => true,
                'message' => 'Search completed successfully',
                'data'    => [
                    'query'      => $query,
                    'type'       => $type,
                    'total'      => $totalCount,
                    'results'    => $results,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function searchStudents(string $query, int $limit)
    {
        try {
            return Student::with(['class', 'section'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('admission_number', 'like', "%{$query}%")
                      ->orWhere('student_id', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->limit($limit)
                ->get()
                ->map(fn ($s) => [
                    'id'               => $s->id,
                    'name'             => $s->name,
                    'admission_number' => $s->admission_number,
                    'class'            => $s->class->name ?? null,
                    'section'          => $s->section->name ?? null,
                    'status'           => $s->is_active ? 'active' : 'inactive',
                    'type'             => 'student',
                ]);
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function searchStaff(string $query, int $limit)
    {
        try {
            return Staff::with(['department', 'role'])
                ->where(function ($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%")
                      ->orWhere('other_names', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%")
                      ->orWhere('staff_id', 'like', "%{$query}%")
                      ->orWhere('job_title', 'like', "%{$query}%");
                })
                ->limit($limit)
                ->get()
                ->map(fn ($s) => [
                    'id'        => $s->id,
                    'name'      => trim($s->first_name . ' ' . $s->last_name),
                    'staff_id'  => $s->staff_id,
                    'email'     => $s->email,
                    'department'=> $s->department->name ?? null,
                    'job_title' => $s->job_title,
                    'status'    => $s->status,
                    'type'      => 'staff',
                ]);
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function searchBooks(string $query, int $limit)
    {
        try {
            return Book::with(['author', 'category'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('isbn', 'like', "%{$query}%")
                      ->orWhere('isbn_13', 'like', "%{$query}%")
                      ->orWhereHas('author', fn ($aq) => $aq->where('name', 'like', "%{$query}%"));
                })
                ->limit($limit)
                ->get()
                ->map(fn ($b) => [
                    'id'       => $b->id,
                    'title'    => $b->title,
                    'isbn'     => $b->isbn,
                    'author'   => $b->author->name ?? null,
                    'category' => $b->category->name ?? null,
                    'status'   => $b->status,
                    'type'     => 'book',
                ]);
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function searchFees(string $query, int $limit)
    {
        try {
            return Fee::with(['category', 'type'])
                ->where('name', 'like', "%{$query}%")
                ->limit($limit)
                ->get()
                ->map(fn ($f) => [
                    'id'       => $f->id,
                    'name'     => $f->name,
                    'amount'   => $f->amount,
                    'category' => $f->category->name ?? null,
                    'type'     => 'fee',
                ]);
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function searchInvoices(string $query, int $limit)
    {
        try {
            return Invoice::with(['student'])
                ->where('invoice_number', 'like', "%{$query}%")
                ->orWhereHas('student', fn ($sq) => $sq->where('name', 'like', "%{$query}%"))
                ->limit($limit)
                ->get()
                ->map(fn ($i) => [
                    'id'              => $i->id,
                    'invoice_number'  => $i->invoice_number,
                    'student_name'    => $i->student->name ?? null,
                    'total_amount'    => $i->total_amount,
                    'status'          => $i->status,
                    'type'            => 'invoice',
                ]);
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function searchPayments(string $query, int $limit)
    {
        try {
            return Payment::with(['invoice', 'invoice.student'])
                ->where('reference_number', 'like', "%{$query}%")
                ->orWhereHas('invoice', fn ($iq) => $iq->where('invoice_number', 'like', "%{$query}%"))
                ->limit($limit)
                ->get()
                ->map(fn ($p) => [
                    'id'               => $p->id,
                    'reference_number' => $p->reference_number,
                    'amount'           => $p->amount,
                    'method'           => $p->payment_method,
                    'invoice_number'   => $p->invoice->invoice_number ?? null,
                    'student_name'     => $p->invoice->student->name ?? null,
                    'date'             => $p->payment_date,
                    'type'             => 'payment',
                ]);
        } catch (\Exception $e) {
            return collect();
        }
    }
}
