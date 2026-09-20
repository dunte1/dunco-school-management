<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Document;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents.
     */
    public function index(Request $request)
    {
        $query = Document::with(['uploader', 'school'])
            ->where('is_active', true)
            ->orderByDesc('created_at');

        if ($request->filled('category')) {
            $query->ofCategory($request->category);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($request->filled('visibility')) {
            $query->where('visibility', $request->visibility);
        }

        $documents = $query->paginate(15);
        $categories = Document::distinct()->pluck('category')->filter()->values();

        return view('document::index', compact('documents', 'categories'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        $categories = Document::distinct()->pluck('category')->filter()->values();
        return view('document::create', compact('categories'));
    }

    /**
     * Store a newly uploaded document.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => 'required|file|max:51200',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'visibility' => 'required|in:public,private,staff_only,students_only',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'local');

        $tags = null;
        if (!empty($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
        }

        Document::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'category' => $validated['category'] ?? null,
            'tags' => $tags,
            'visibility' => $validated['visibility'],
            'uploaded_by' => auth()->id(),
            'school_id' => auth()->user()->school_id ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('document.index')->with('success', 'Document uploaded successfully.');
    }

    /**
     * Display the specified document.
     */
    public function show($id)
    {
        $document = Document::with(['uploader', 'school'])->findOrFail($id);
        return view('document::show', compact('document'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $categories = Document::distinct()->pluck('category')->filter()->values();
        return view('document::edit', compact('document', 'categories'));
    }

    /**
     * Update the specified document.
     */
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'visibility' => 'required|in:public,private,staff_only,students_only',
            'file' => 'nullable|file|max:51200',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
            'visibility' => $validated['visibility'],
        ];

        if (!empty($validated['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

        if ($request->hasFile('file')) {
            Storage::disk('local')->delete($document->file_path);
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $data['file_path'] = $file->storeAs('documents', $fileName, 'local');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $file->getClientOriginalExtension();
            $data['file_size'] = $file->getSize();
        }

        $document->update($data);

        return redirect()->route('document.show', $id)->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified document.
     */
    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        Storage::disk('local')->delete($document->file_path);
        $document->delete();

        return redirect()->route('document.index')->with('success', 'Document deleted successfully.');
    }

    /**
     * Show upload page.
     */
    public function upload()
    {
        $categories = Document::distinct()->pluck('category')->filter()->values();
        return view('document::upload', compact('categories'));
    }

    /**
     * Handle file upload from upload page.
     */
    public function handleUpload(Request $request)
    {
        $validated = $request->validate([
            'files' => 'required|array|max:10',
            'files.*' => 'file|max:51200',
            'category' => 'nullable|string|max:100',
            'visibility' => 'required|in:public,private,staff_only,students_only',
        ]);

        foreach ($request->file('files') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'local');

            Document::create([
                'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'category' => $validated['category'] ?? null,
                'visibility' => $validated['visibility'],
                'uploaded_by' => auth()->id(),
                'school_id' => auth()->user()->school_id ?? null,
                'is_active' => true,
            ]);
        }

        return redirect()->route('document.index')->with('success', count($request->file('files')) . ' file(s) uploaded successfully.');
    }

    /**
     * Download a document.
     */
    public function download($id)
    {
        $document = Document::findOrFail($id);
        $path = $document->file_path;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('local')->download($path, $document->file_name);
    }

    /**
     * Manage documents page.
     */
    public function manage()
    {
        $documents = Document::with(['uploader'])
            ->orderByDesc('created_at')
            ->paginate(20);

        $stats = [
            'total' => Document::count(),
            'active' => Document::where('is_active', true)->count(),
            'inactive' => Document::where('is_active', false)->count(),
            'categories' => Document::distinct()->count('category'),
        ];

        return view('document::manage', compact('documents', 'stats'));
    }

    /**
     * Toggle document active status.
     */
    public function toggleStatus($id)
    {
        $document = Document::findOrFail($id);
        $document->update(['is_active' => !$document->is_active]);

        return back()->with('success', 'Document status updated.');
    }
}
