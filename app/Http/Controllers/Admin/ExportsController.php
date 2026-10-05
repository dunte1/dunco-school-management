<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExportsController extends Controller
{
    public function index()
    {
        $exportHistory = $this->getExportHistory();
        
        return view('admin.exports.index', compact('exportHistory'));
    }
    
    private function getExportHistory()
    {
        // Placeholder for export history
        return [
            [
                'id' => 1,
                'type' => 'Student Report',
                'filename' => 'student_report_2024.xlsx',
                'status' => 'completed',
                'records_exported' => 150,
                'file_size' => '2.5 MB',
                'created_at' => now()->subDays(1),
                'user' => 'Admin User'
            ],
            [
                'id' => 2,
                'type' => 'Attendance Report',
                'filename' => 'attendance_report_jan_2024.csv',
                'status' => 'completed',
                'records_exported' => 1200,
                'file_size' => '1.8 MB',
                'created_at' => now()->subDays(3),
                'user' => 'Admin User'
            ]
        ];
    }
}
