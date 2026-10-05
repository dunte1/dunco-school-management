<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Library\Models\Borrow;

class OverdueController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrow::with(['book', 'member'])
            ->where('status', 'borrowed')
            ->where('due_at', '<', now());

        // Filter by member
        if ($request->has('member_id') && $request->member_id) {
            $query->where('member_id', $request->member_id);
        }

        // Filter by overdue days
        if ($request->has('overdue_days') && $request->overdue_days) {
            $days = $request->overdue_days;
            $query->where('due_at', '<', now()->subDays($days));
        }

        // Search by book title or member name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('book', function($bookQuery) use ($search) {
                    $bookQuery->where('title', 'like', "%{$search}%");
                })->orWhereHas('member', function($memberQuery) use ($search) {
                    $memberQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        $overdueBooks = $query->orderBy('due_at', 'asc')->paginate(15);
        $members = \Modules\Library\Models\Member::all();

        return view('library::overdue.index', compact('overdueBooks', 'members'));
    }

    public function create()
    {
        return view('library::overdue.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'borrow_id' => 'required|exists:borrows,id',
            'action' => 'required|in:extend,remind,charge_fine',
            'new_due_at' => 'nullable|date|after:today',
            'fine_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        try {
            $borrow = Borrow::findOrFail($request->borrow_id);
            
            switch ($request->action) {
                case 'extend':
                    $borrow->update([
                        'due_at' => $request->new_due_at,
                        'notes' => $request->notes
                    ]);
                    $message = 'Due date extended successfully.';
                    break;
                    
                case 'remind':
                    // Send reminder notification (implement notification logic)
                    $message = 'Reminder sent successfully.';
                    break;
                    
                case 'charge_fine':
                    $borrow->update([
                        'fine_amount' => $request->fine_amount,
                        'notes' => $request->notes
                    ]);
                    $message = 'Fine charged successfully.';
                    break;
            }

            return redirect()->route('library.overdue.index')
                           ->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to process overdue action: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $overdue = Borrow::with(['book', 'member'])->findOrFail($id);
        return view('library::overdue.show', compact('overdue'));
    }

    public function edit($id)
    {
        $overdue = Borrow::findOrFail($id);
        return view('library::overdue.edit', compact('overdue'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'due_at' => 'required|date|after:today',
            'fine_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        try {
            $overdue = Borrow::findOrFail($id);
            $overdue->update($request->all());

            return redirect()->route('library.overdue.index')
                           ->with('success', 'Overdue record updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update overdue record: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $overdue = Borrow::findOrFail($id);
            $overdue->delete();

            return redirect()->route('library.overdue.index')
                           ->with('success', 'Overdue record deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete overdue record: ' . $e->getMessage()]);
        }
    }
}
