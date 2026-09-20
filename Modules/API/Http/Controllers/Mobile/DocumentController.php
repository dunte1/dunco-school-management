<?php

namespace Modules\API\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Academic\Models\StudentDocument;
use Modules\Academic\Models\Student;

class DocumentController extends Controller
{
    public function getDocuments(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $student = Student::where('user_id', $userId)->first();

            $query = StudentDocument::with(['student']);

            if ($student) {
                $query->where('student_id', $student->id);
            }

            if ($request->filled('type')) {
                $query->where('document_type', $request->type);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }

            $documents = $query->orderByDesc('created_at')->paginate(25);

            return response()->json([
                'success' => true,
                'message' => 'Documents retrieved successfully',
                'data' => $documents
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve documents: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDocument($id): JsonResponse
    {
        try {
            $document = StudentDocument::with(['student', 'uploadedBy'])->find($id);

            if (!$document) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Document retrieved successfully',
                'data' => $document
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadDocument(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_type' => 'required|string|max:100',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userId = Auth::id();
            $student = Student::where('user_id', $userId)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student profile not found'
                ], 404);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('student-documents', $fileName, 'public');

            $document = StudentDocument::create([
                'student_id' => $student->id,
                'title' => $request->title,
                'description' => $request->description,
                'document_type' => $request->document_type,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'data' => $document
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadDocument($id): JsonResponse
    {
        try {
            $document = StudentDocument::find($id);

            if (!$document) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found'
                ], 404);
            }

            $filePath = $document->file_path;

            if (!Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found on disk'
                ], 404);
            }

            $url = Storage::disk('public')->url($filePath);

            return response()->json([
                'success' => true,
                'message' => 'Download URL retrieved successfully',
                'data' => [
                    'url' => $url,
                    'file_name' => $document->file_name,
                    'mime_type' => $document->mime_type,
                    'file_size' => $document->file_size
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get download URL: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteDocument($id): JsonResponse
    {
        try {
            $document = StudentDocument::find($id);

            if (!$document) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found'
                ], 404);
            }

            // Delete file from disk
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDocumentTypes(): JsonResponse
    {
        try {
            $types = StudentDocument::distinct()
                ->pluck('document_type')
                ->filter()
                ->values();

            return response()->json([
                'success' => true,
                'message' => 'Document types retrieved successfully',
                'data' => $types
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve document types: ' . $e->getMessage()
            ], 500);
        }
    }
}
