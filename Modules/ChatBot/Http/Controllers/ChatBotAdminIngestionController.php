<?php

namespace Modules\ChatBot\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ChatBot\Models\Document;
use Modules\ChatBot\Models\Index as VectorIndex;
use Modules\ChatBot\Services\DocumentService;

class ChatBotAdminIngestionController extends Controller
{
    public function index()
    {
        $docs = Document::orderByDesc('id')->paginate(20);
        return view('chatbot::admin.ingestion', compact('docs'));
    }

    public function upload(Request $request, DocumentService $service)
    {
        $request->validate(['file' => 'required|file', 'description' => 'nullable|string']);
        $doc = $service->uploadDocument($request->file('file'), $request->user()->id, null, $request->input('description'));
        // naive chunking
        $text = $doc->content_text ?? '';
        $chunks = str_split($text, 1000);
        foreach ($chunks as $i => $chunk) {
            VectorIndex::create([
                'tenant_id' => tenant('id') ?? null,
                'document_id' => $doc->id,
                'chunk_index' => $i,
                'chunk' => $chunk,
                'embedding' => null,
            ]);
        }
        return back()->with('success','Document ingested');
    }
}


