<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Document\Models\Document;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_documents' => Document::count(),
            'total_uploads' => Document::where('created_at', '>=', now()->startOfMonth())->count(),
            'total_downloads' => Document::sum('download_count'),
            'total_shares' => \Modules\Document\Models\DocumentShare::count(),
            'storage_used' => Document::sum('file_size'),
        ];

        return view('document::reports.index', compact('stats'));
    }

    public function uploads()
    {
        $uploads = Document::with('category')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('document::reports.uploads', compact('uploads'));
    }

    public function downloads()
    {
        $downloads = Document::with('category')
            ->orderBy('download_count', 'desc')
            ->take(20)
            ->get();

        return view('document::reports.downloads', compact('downloads'));
    }

    public function sharing()
    {
        $shares = \Modules\Document\Models\DocumentShare::with(['document', 'sharedWith'])
            ->selectRaw('document_id, COUNT(*) as share_count')
            ->groupBy('document_id')
            ->orderBy('share_count', 'desc')
            ->take(20)
            ->get();

        return view('document::reports.sharing', compact('shares'));
    }

    public function storage()
    {
        $storageByCategory = Document::with('category')
            ->selectRaw('category_id, SUM(file_size) as total_size, COUNT(*) as document_count')
            ->groupBy('category_id')
            ->orderBy('total_size', 'desc')
            ->get();

        return view('document::reports.storage', compact('storageByCategory'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'uploads');
        
        // Basic export functionality
        return response()->json([
            'message' => "Exporting {$type} report...",
            'type' => $type
        ]);
    }
}
