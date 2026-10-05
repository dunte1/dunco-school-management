<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Document\Models\Document;

class AnalyticsController extends Controller
{
    public function index()
    {
        $analytics = [
            'total_documents' => Document::count(),
            'total_storage' => Document::sum('file_size'),
            'avg_file_size' => Document::avg('file_size'),
            'most_downloaded' => Document::orderBy('download_count', 'desc')->first(),
            'recent_uploads' => Document::orderBy('created_at', 'desc')->take(5)->get(),
        ];

        return view('document::analytics.index', compact('analytics'));
    }

    public function usage()
    {
        $usage = [
            'daily_uploads' => Document::where('created_at', '>=', now()->subDays(7))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get(),
            'top_categories' => Document::with('category')
                ->selectRaw('category_id, COUNT(*) as count')
                ->groupBy('category_id')
                ->orderBy('count', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('document::analytics.usage', compact('usage'));
    }

    public function storage()
    {
        $storage = [
            'by_category' => Document::with('category')
                ->selectRaw('category_id, SUM(file_size) as total_size, COUNT(*) as count')
                ->groupBy('category_id')
                ->orderBy('total_size', 'desc')
                ->get(),
            'by_month' => Document::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(file_size) as total_size')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get(),
        ];

        return view('document::analytics.storage', compact('storage'));
    }

    public function performance()
    {
        $performance = [
            'upload_trends' => Document::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get(),
            'download_trends' => Document::selectRaw('DATE(updated_at) as date, SUM(download_count) as count')
                ->where('updated_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get(),
        ];

        return view('document::analytics.performance', compact('performance'));
    }
}
