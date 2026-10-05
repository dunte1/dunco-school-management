<?php

namespace App\Http\Controllers\Examinations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $items = collect([
            ['subject' => 'Mathematics', 'class' => 'Grade 8', 'date' => now()->addDays(1)->toDateString(), 'time' => '10:00', 'supervisor' => 'Mr. White'],
            ['subject' => 'English', 'class' => 'Grade 7', 'date' => now()->addDays(2)->toDateString(), 'time' => '09:00', 'supervisor' => 'Ms. Green'],
            ['subject' => 'Science', 'class' => 'Grade 9', 'date' => now()->addDays(3)->toDateString(), 'time' => '11:30', 'supervisor' => 'Dr. Brown'],
        ]);

        $perPage = 10; $page = LengthAwarePaginator::resolveCurrentPage();
        $paginator = new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('examinations.schedule', ['exams' => $paginator]);
    }
}
