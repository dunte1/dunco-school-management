<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\Subject;
use Modules\Academic\Models\SubjectResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SubjectResourceController extends Controller
{
    // List resources for a subject
    public function index($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        $resources = $subject->resources;
        return response()->json(['resources' => $resources]);
    }

    // Upload a new resource (file or link)
    public function store(Request $request, $subjectId)
    {
        $request->validate([
            'type' => 'required|in:file,link',
            'title' => 'required|string|max:255',
            'url' => 'nullable|url',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        $data = [
            'subject_id' => $subjectId,
            'type' => $request->type,
            'title' => $request->title,
            'uploaded_by' => Auth::id(),
        ];

        if ($request->type === 'file' && $request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('subject_resources');
        }
        if ($request->type === 'link') {
            $data['url'] = $request->url;
        }

        $resource = SubjectResource::create($data);

        return response()->json(['success' => true, 'resource' => $resource]);
    }

    // Delete a resource
    public function destroy($subjectId, $resourceId)
    {
        $resource = SubjectResource::where('subject_id', $subjectId)->findOrFail($resourceId);
        if ($resource->type === 'file' && $resource->file_path) {
            Storage::delete($resource->file_path);
        }
        $resource->delete();
        return response()->json(['success' => true, 'message' => 'Resource deleted.']);
    }
}
