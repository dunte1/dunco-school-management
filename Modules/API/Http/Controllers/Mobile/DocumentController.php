<?php

namespace Modules\API\Http\Controllers\Mobile;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class DocumentController extends Controller
{
    public function getDocuments(): JsonResponse
    {
        $documents = \Modules\Academic\Models\StudentDocument::orderByDesc('created_at')->paginate(25);
        return response()->json(['success' => true, 'data' => $documents]);
    }

    public function getDocument($id): JsonResponse
    {
        $document = \Modules\Academic\Models\StudentDocument::find($id);
        if (!$document) return response()->json(['message' => 'Document not found'], 404);
        return response()->json(['success' => true, 'data' => $document]);
    }
}
