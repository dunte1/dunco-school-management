<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Document\Models\Template;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = Template::withCount('documents');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $templates = $query->orderBy('name', 'asc')->paginate(15);

        return view('document::templates.index', compact('templates'));
    }

    public function create()
    {
        return view('document::templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'is_active' => 'boolean'
        ]);

        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();

            Template::create($data);

            return redirect()->route('document.templates.index')
                           ->with('success', 'Template created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create template: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $template = Template::withCount('documents')->findOrFail($id);
        return view('document::templates.show', compact('template'));
    }

    public function edit($id)
    {
        $template = Template::findOrFail($id);
        return view('document::templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'is_active' => 'boolean'
        ]);

        try {
            $template = Template::findOrFail($id);
            $template->update($request->all());

            return redirect()->route('document.templates.index')
                           ->with('success', 'Template updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update template: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $template = Template::findOrFail($id);
            $template->delete();

            return redirect()->route('document.templates.index')
                           ->with('success', 'Template deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete template: ' . $e->getMessage()]);
        }
    }
}
