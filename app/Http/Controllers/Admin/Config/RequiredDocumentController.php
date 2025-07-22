<?php
namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Models\RequiredDocument;
use Illuminate\Http\Request;

class RequiredDocumentController extends Controller
{
    public function index()
    {
        $documents = RequiredDocument::all();
        return view('admin.config.documents.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'applies_to' => 'nullable|array',
            'is_active' => 'boolean',
        ]);
        $data['applies_to'] = $data['applies_to'] ?? [];
        $document = RequiredDocument::create($data);
        return response()->json(['success' => true, 'document' => $document]);
    }

    public function update(Request $request, RequiredDocument $requiredDocument)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'applies_to' => 'nullable|array',
            'is_active' => 'boolean',
        ]);
        $data['applies_to'] = $data['applies_to'] ?? [];
        $requiredDocument->update($data);
        return response()->json(['success' => true, 'document' => $requiredDocument]);
    }

    public function destroy(RequiredDocument $requiredDocument)
    {
        $requiredDocument->delete();
        return response()->json(['success' => true]);
    }
} 