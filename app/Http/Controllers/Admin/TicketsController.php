<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }
        $tickets = $query->orderByDesc('created_at')->paginate(15);
        return view('admin.helpdesk.index', compact('tickets'));
    }

    public function create()
    {
        return view('admin.helpdesk.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'school_id' => 'nullable|integer',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|string|in:low,medium,high,critical',
        ]);
        $data['status'] = 'open';
        $data['created_by'] = auth()->id();
        Ticket::create($data);
        return redirect()->route('admin.helpdesk.tickets.index')->with('success', 'Ticket created');
    }

    public function edit(Ticket $ticket)
    {
        return view('admin.helpdesk.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:open,pending,in_progress,resolved,closed',
            'priority' => 'required|string|in:low,medium,high,critical',
            'assigned_to' => 'nullable|integer',
        ]);
        if ($data['status'] === 'closed' && !$ticket->closed_at) {
            $data['closed_at'] = now();
        }
        $ticket->update($data);
        return redirect()->route('admin.helpdesk.tickets.index')->with('success', 'Ticket updated');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.helpdesk.tickets.index')->with('success', 'Ticket deleted');
    }
}
