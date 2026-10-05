<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Communication\Models\Template;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::with('creator')
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%");
            })
            ->when(request('type'), function ($query, $type) {
                $query->where('type', $type);
            })
            ->latest()
            ->paginate(15);

        return view('communication::templates.index', compact('templates'));
    }

    public function create()
    {
        return view('communication::templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|in:email,sms,notification',
            'is_active' => 'boolean'
        ]);

        $template = Template::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('communication.templates.index')
            ->with('success', 'Template created successfully.');
    }

    public function show(Template $template)
    {
        return view('communication::templates.show', compact('template'));
    }

    public function edit(Template $template)
    {
        return view('communication::templates.edit', compact('template'));
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|in:email,sms,notification',
            'is_active' => 'boolean'
        ]);

        $template->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('communication.templates.index')
            ->with('success', 'Template updated successfully.');
    }

    public function destroy(Template $template)
    {
        $template->delete();

        return redirect()->route('communication.templates.index')
            ->with('success', 'Template deleted successfully.');
    }

    public function toggleStatus(Template $template)
    {
        $template->update([
            'is_active' => !$template->is_active,
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('communication.templates.index')
            ->with('success', 'Template status updated successfully.');
    }
}
