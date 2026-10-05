<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Document\Models\Workflow;

class WorkflowController extends Controller
{
    public function index(Request $request)
    {
        $query = Workflow::withCount('documents');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $workflows = $query->orderBy('name', 'asc')->paginate(15);

        return view('document::workflows.index', compact('workflows'));
    }

    public function create()
    {
        return view('document::workflows.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'steps' => 'required|array',
            'is_active' => 'boolean'
        ]);

        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();
            $data['steps'] = json_encode($request->steps);

            Workflow::create($data);

            return redirect()->route('document.workflows.index')
                           ->with('success', 'Workflow created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create workflow: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $workflow = Workflow::withCount('documents')->findOrFail($id);
        return view('document::workflows.show', compact('workflow'));
    }

    public function edit($id)
    {
        $workflow = Workflow::findOrFail($id);
        return view('document::workflows.edit', compact('workflow'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'steps' => 'required|array',
            'is_active' => 'boolean'
        ]);

        try {
            $workflow = Workflow::findOrFail($id);
            $data = $request->all();
            $data['steps'] = json_encode($request->steps);
            $workflow->update($data);

            return redirect()->route('document.workflows.index')
                           ->with('success', 'Workflow updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update workflow: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $workflow = Workflow::findOrFail($id);
            $workflow->delete();

            return redirect()->route('document.workflows.index')
                           ->with('success', 'Workflow deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete workflow: ' . $e->getMessage()]);
        }
    }
}
