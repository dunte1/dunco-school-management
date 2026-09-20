<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\DemoRequest;
use Illuminate\Http\Request;

class DemoRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = DemoRequest::query();

        if ($status = $request->input('status')) {
            $query->byStatus($status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        $demos = $query->latest()->paginate(20);

        $statuses = ['new', 'contacted', 'scheduled', 'completed', 'converted', 'lost', 'closed'];
        $counts = collect($statuses)->mapWithKeys(fn($s) => [$s => DemoRequest::byStatus($s)->count()])->toArray();

        return view('admin.cms.demos.index', compact('demos', 'counts'));
    }

    public function show(DemoRequest $demoRequest)
    {
        return view('admin.cms.demos.show', ['demo' => $demoRequest]);
    }

    public function updateStatus(Request $request, DemoRequest $demoRequest)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,scheduled,completed,converted,lost,closed',
        ]);

        $demoRequest->update([
            'status' => $request->input('status'),
            'notes'  => $request->input('notes') ?? $demoRequest->notes,
        ]);

        return redirect()->route('admin.cms.demos.show', $demoRequest)
            ->with('success', 'Demo request status updated.');
    }

    public function destroy(DemoRequest $demoRequest)
    {
        $demoRequest->delete();

        return redirect()->route('admin.cms.demos.index')
            ->with('success', 'Demo request deleted.');
    }
}
