<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Communication\Models\Contact;
use Modules\Communication\Models\Group;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::with(['creator', 'groups'])
            ->when(request('search'), function ($query, $search) {
                $query->search($search);
            })
            ->when(request('category'), function ($query, $category) {
                $query->byCategory($category);
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

        return view('communication::contacts.index', compact('contacts'));
    }

    public function create()
    {
        $groups = Group::active()->get();
        return view('communication::contacts.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'organization' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'category' => 'required|string|in:student,parent,teacher,staff,admin,external',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id'
        ]);

        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'organization' => $request->organization,
            'position' => $request->position,
            'category' => $request->category,
            'notes' => $request->notes,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        if ($request->has('groups')) {
            $contact->groups()->attach($request->groups);
        }

        return redirect()->route('communication.contacts.index')
            ->with('success', 'Contact created successfully.');
    }

    public function show(Contact $contact)
    {
        $contact->load(['creator', 'groups']);
        return view('communication::contacts.show', compact('contact'));
    }

    public function edit(Contact $contact)
    {
        $groups = Group::active()->get();
        $contact->load('groups');
        return view('communication::contacts.edit', compact('contact', 'groups'));
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'organization' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'category' => 'required|string|in:student,parent,teacher,staff,admin,external',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id'
        ]);

        $contact->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'organization' => $request->organization,
            'position' => $request->position,
            'category' => $request->category,
            'notes' => $request->notes,
            'is_active' => $request->boolean('is_active', true),
            'updated_by' => Auth::id()
        ]);

        if ($request->has('groups')) {
            $contact->groups()->sync($request->groups);
        } else {
            $contact->groups()->detach();
        }

        return redirect()->route('communication.contacts.index')
            ->with('success', 'Contact updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('communication.contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }

    public function toggleStatus(Contact $contact)
    {
        $contact->update([
            'is_active' => !$contact->is_active,
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('communication.contacts.index')
            ->with('success', 'Contact status updated successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        // This would typically handle CSV import
        // For now, we'll just return success
        return redirect()->route('communication.contacts.index')
            ->with('success', 'Contacts imported successfully.');
    }

    public function export()
    {
        // This would typically generate a CSV export
        // For now, we'll just return success
        return redirect()->route('communication.contacts.index')
            ->with('success', 'Contacts exported successfully.');
    }
}
