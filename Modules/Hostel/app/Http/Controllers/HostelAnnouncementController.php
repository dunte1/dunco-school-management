<?php

namespace Modules\Hostel\Http\Controllers;

use Modules\Hostel\Models\HostelAnnouncement;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Models\Warden;
use Modules\Hostel\Http\Requests\StoreHostelAnnouncementRequest;
use Modules\Hostel\Http\Requests\UpdateHostelAnnouncementRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HostelAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $announcements = HostelAnnouncement::with(['hostel', 'warden'])
            ->when($request->input('hostel_id'), fn($q, $hostelId) => $q->where('hostel_id', $hostelId))
            ->when($request->input('audience'), fn($q, $audience) => $q->where('audience', $audience))
            ->latest()->paginate(15);
        $hostels = Hostel::all();
        return view('hostel::announcements.index', compact('announcements', 'hostels'));
    }

    public function create()
    {
        $hostels = Hostel::all();
        $wardens = Warden::all();
        return view('hostel::announcements.create', compact('hostels', 'wardens'));
    }

    public function store(StoreHostelAnnouncementRequest $request)
    {
        HostelAnnouncement::create($request->validated());
        return redirect()->route('hostel.announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function show(HostelAnnouncement $hostelAnnouncement)
    {
        $hostelAnnouncement->load(['hostel', 'warden']);
        return view('hostel::announcements.show', compact('hostelAnnouncement'));
    }

    public function edit(HostelAnnouncement $hostelAnnouncement)
    {
        $hostels = Hostel::all();
        $wardens = Warden::all();
        return view('hostel::announcements.edit', compact('hostelAnnouncement', 'hostels', 'wardens'));
    }

    public function update(UpdateHostelAnnouncementRequest $request, HostelAnnouncement $hostelAnnouncement)
    {
        $hostelAnnouncement->update($request->validated());
        return redirect()->route('hostel.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(HostelAnnouncement $hostelAnnouncement)
    {
        $hostelAnnouncement->delete();
        return redirect()->route('hostel.announcements.index')->with('success', 'Announcement deleted successfully.');
    }
} 