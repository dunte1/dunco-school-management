<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\ContactEnquiry;
use Illuminate\Http\Request;

class ContactEnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactEnquiry::query();

        if ($status = $request->input('status')) {
            $query->byStatus($status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%");
            });
        }

        $enquiries = $query->latest()->paginate(20);

        $statuses = ['new', 'contacted', 'resolved', 'closed'];
        $counts = collect($statuses)->mapWithKeys(fn($s) => [$s => ContactEnquiry::byStatus($s)->count()])->toArray();

        return view('admin.cms.enquiries.index', compact('enquiries', 'counts'));
    }

    public function show(ContactEnquiry $enquiry)
    {
        return view('admin.cms.enquiries.show', compact('enquiry'));
    }

    public function updateStatus(Request $request, ContactEnquiry $enquiry)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,resolved,closed',
        ]);

        $enquiry->update([
            'status' => $request->input('status'),
            'notes'  => $request->input('notes') ?? $enquiry->notes,
        ]);

        return redirect()->route('admin.cms.enquiries.show', $enquiry)
            ->with('success', 'Enquiry status updated.');
    }

    public function destroy(ContactEnquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('admin.cms.enquiries.index')
            ->with('success', 'Enquiry deleted.');
    }
}
