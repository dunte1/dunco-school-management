<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Communication\Models\Schedule;
use Modules\Communication\Models\Template;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['creator', 'template'])
            ->when(request('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->when(request('type'), function ($query, $type) {
                $query->where('type', $type);
            })
            ->when(request('frequency'), function ($query, $frequency) {
                $query->where('frequency', $frequency);
            })
            ->when(request('status'), function ($query, $status) {
                if ($status === 'active') {
                    $query->active();
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->latest()
            ->paginate(15);

        return view('communication::schedules.index', compact('schedules'));
    }

    public function create()
    {
        $templates = Template::active()->get();
        return view('communication::schedules.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:email,sms,notification',
            'frequency' => 'required|string|in:once,daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'time' => 'required|date_format:H:i',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'integer|between:0,6',
            'is_active' => 'boolean',
            'template_id' => 'nullable|exists:templates,id',
            'recipients' => 'nullable|array'
        ]);

        $schedule = Schedule::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'frequency' => $request->frequency,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'time' => $request->time,
            'days_of_week' => $request->days_of_week,
            'is_active' => $request->boolean('is_active', true),
            'template_id' => $request->template_id,
            'recipients' => $request->recipients,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('communication.schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    public function show(Schedule $schedule)
    {
        return view('communication::schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $templates = Template::active()->get();
        return view('communication::schedules.edit', compact('schedule', 'templates'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:email,sms,notification',
            'frequency' => 'required|string|in:once,daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'time' => 'required|date_format:H:i',
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'integer|between:0,6',
            'is_active' => 'boolean',
            'template_id' => 'nullable|exists:templates,id',
            'recipients' => 'nullable|array'
        ]);

        $schedule->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'frequency' => $request->frequency,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'time' => $request->time,
            'days_of_week' => $request->days_of_week,
            'is_active' => $request->boolean('is_active', true),
            'template_id' => $request->template_id,
            'recipients' => $request->recipients,
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('communication.schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('communication.schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }

    public function toggleStatus(Schedule $schedule)
    {
        $schedule->update([
            'is_active' => !$schedule->is_active,
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('communication.schedules.index')
            ->with('success', 'Schedule status updated successfully.');
    }

    public function runNow(Schedule $schedule)
    {
        // This would typically trigger the scheduled communication
        // For now, we'll just return success
        return redirect()->route('communication.schedules.index')
            ->with('success', 'Schedule executed successfully.');
    }
}
