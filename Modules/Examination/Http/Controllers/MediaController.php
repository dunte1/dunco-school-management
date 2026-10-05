<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // 100MB max
            'type' => 'required|in:image,audio,video,document',
        ]);

        $file = $request->file('file');
        $type = $request->input('type');
        $path = $file->store('examination/media/' . $type, 'public');

        $mimeType = $file->getMimeType();
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize();
        $duration = null; // Implement logic to get duration for audio/video if needed

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully.',
            'file' => [
                'name' => $originalName,
                'path' => $path,
                'url' => Storage::url($path),
                'mime_type' => $mimeType,
                'extension' => $extension,
                'size' => $size,
                'duration' => $duration,
                'uploaded_at' => now()->timestamp,
            ]
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        if (Storage::disk('public')->exists($request->path)) {
            Storage::disk('public')->delete($request->path);
            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'File not found.'], 404);
    }

    public function serve($type, $filename)
    {
        $path = 'examination/media/' . $type . '/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);

        $response = new StreamedResponse(function () use ($path) {
            $stream = Storage::disk('public')->readStream($path);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        });

        $response->headers->set('Content-Type', $mimeType);
        $response->headers->set('Content-Length', Storage::disk('public')->size($path));
        $response->headers->set('Content-Disposition', 'inline; filename="' . basename($filename) . '"');

        return $response;
    }

    public function getSupportedFormats()
    {
        $formats = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
            'audio' => ['mp3', 'wav', 'ogg'],
            'video' => ['mp4', 'webm', 'ogg'],
            'document' => ['pdf', 'doc', 'docx', 'txt'],
        ];
        
        return view('examination::media.formats', compact('formats'));
    }
}