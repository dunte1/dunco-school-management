<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Document\Models\DocumentVersion;

class VersionController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentVersion::with(['document', 'createdBy']);

        // Filter by document
        if ($request->has('document_id') && $request->document_id) {
            $query->where('document_id', $request->document_id);
        }

        // Search by version number or comment
        if ($request->has('search') && $request->search) {
            $query->where('version_number', 'like', '%' . $request->search . '%')
                  ->orWhere('comment', 'like', '%' . $request->search . '%');
        }

        $versions = $query->orderBy('created_at', 'desc')->paginate(15);
        $documents = \Modules\Document\Models\Document::all();

        return view('document::versions.index', compact('versions', 'documents'));
    }

    public function create()
    {
        $documents = \Modules\Document\Models\Document::all();
        return view('document::versions.create', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'version_number' => 'required|string|max:50',
            'file_path' => 'required|string',
            'file_size' => 'required|numeric',
            'comment' => 'nullable|string'
        ]);

        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();

            DocumentVersion::create($data);

            return redirect()->route('document.versions.index')
                           ->with('success', 'Version created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create version: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $version = DocumentVersion::with(['document', 'createdBy'])->findOrFail($id);
        return view('document::versions.show', compact('version'));
    }

    public function edit($id)
    {
        $version = DocumentVersion::findOrFail($id);
        $documents = \Modules\Document\Models\Document::all();
        return view('document::versions.edit', compact('version', 'documents'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'version_number' => 'required|string|max:50',
            'file_path' => 'required|string',
            'file_size' => 'required|numeric',
            'comment' => 'nullable|string'
        ]);

        try {
            $version = DocumentVersion::findOrFail($id);
            $version->update($request->all());

            return redirect()->route('document.versions.index')
                           ->with('success', 'Version updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update version: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $version = DocumentVersion::findOrFail($id);
            $version->delete();

            return redirect()->route('document.versions.index')
                           ->with('success', 'Version deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete version: ' . $e->getMessage()]);
        }
    }
}
