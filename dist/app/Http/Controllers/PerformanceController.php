<?php

namespace App\Http\Controllers;

use App\Services\PerformanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PerformanceController extends Controller
{
    protected $performanceService;

    public function __construct(PerformanceService $performanceService)
    {
        $this->performanceService = $performanceService;
    }

    /**
     * Display performance dashboard
     */
    public function dashboard()
    {
        $stats = $this->performanceService->getStats();
        
        return view('performance.dashboard', compact('stats'));
    }

    /**
     * Run performance optimization
     */
    public function optimize()
    {
        try {
            $this->performanceService->optimize();
            
            return response()->json([
                'success' => true,
                'message' => 'Performance optimization completed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during optimization: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get performance statistics
     */
    public function stats()
    {
        $stats = $this->performanceService->getStats();
        
        return response()->json($stats);
    }

    /**
     * Clear all caches
     */
    public function clearCaches()
    {
        try {
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing caches: ' . $e->getMessage()
            ], 500);
        }
    }
} 