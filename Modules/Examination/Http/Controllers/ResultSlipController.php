<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PDF;

class ResultSlipController extends Controller
{
    public function show($resultId)
    {
        // In a real app, fetch result with relationships
        $result = [
            'id' => $resultId,
            'student' => ['name' => 'John Doe', 'adm' => 'ADM001'],
            'exam' => ['name' => 'Mathematics Final', 'term' => 'Term 1', 'year' => '2024'],
            'subjects' => [
                ['name' => 'Mathematics', 'score' => 85, 'grade' => 'A'],
                ['name' => 'Physics', 'score' => 78, 'grade' => 'B'],
                ['name' => 'English', 'score' => 92, 'grade' => 'A'],
            ],
            'total' => 255,
            'average' => 85,
            'position' => 3,
        ];

        $pdf = PDF::loadView('examination::results.slip', compact('result'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('result-slip-'.$resultId.'.pdf');
    }
}


