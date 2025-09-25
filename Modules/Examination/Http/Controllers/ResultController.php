<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ResultController extends Controller
{
    public function index()
    {
        $results = collect([
            (object)[
                'id' => 1,
                'exam' => (object)[
                    'name' => 'Mathematics Final',
                    'examType' => (object)['name' => 'Final']
                ],
                'student' => (object)[
                    'name' => 'John Doe',
                    'email' => 'john.doe@example.com'
                ],
                'score' => 85,
                'total_marks' => 100,
                'percentage' => 85,
                'grade' => 'A',
                'is_published' => true,
                'created_at' => \Carbon\Carbon::parse('2024-12-15')
            ],
            (object)[
                'id' => 2,
                'exam' => (object)[
                    'name' => 'Physics Midterm',
                    'examType' => (object)['name' => 'Midterm']
                ],
                'student' => (object)[
                    'name' => 'Jane Smith',
                    'email' => 'jane.smith@example.com'
                ],
                'score' => 78,
                'total_marks' => 100,
                'percentage' => 78,
                'grade' => 'B',
                'is_published' => false,
                'created_at' => \Carbon\Carbon::parse('2024-12-14')
            ],
            (object)[
                'id' => 3,
                'exam' => (object)[
                    'name' => 'English Literature',
                    'examType' => (object)['name' => 'Final']
                ],
                'student' => (object)[
                    'name' => 'Mike Johnson',
                    'email' => 'mike.johnson@example.com'
                ],
                'score' => 92,
                'total_marks' => 100,
                'percentage' => 92,
                'grade' => 'A',
                'is_published' => true,
                'created_at' => \Carbon\Carbon::parse('2024-12-13')
            ],
            (object)[
                'id' => 4,
                'exam' => (object)[
                    'name' => 'Chemistry Final',
                    'examType' => (object)['name' => 'Final']
                ],
                'student' => (object)[
                    'name' => 'Sarah Wilson',
                    'email' => 'sarah.wilson@example.com'
                ],
                'score' => 65,
                'total_marks' => 100,
                'percentage' => 65,
                'grade' => 'C',
                'is_published' => false,
                'created_at' => \Carbon\Carbon::parse('2024-12-12')
            ]
        ]);
        
        // Create a paginated collection
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedResults = $results->slice($offset, $perPage);
        
        $results = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedResults->values(),
            $results->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );
        
        return view('examination::results.index', compact('results'));
    }

    public function create()
    {
        return view('examination::results.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('examination.results.index');
    }

    public function show($id)
    {
        return view('examination::results.show');
    }

    public function edit($id)
    {
        return view('examination::results.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('examination.results.index');
    }

    public function destroy($id)
    {
        return redirect()->route('examination.results.index');
    }

    public function publishResults($exam)
    {
        return redirect()->back()->with('success', 'Results published successfully');
    }

    public function transcript($student)
    {
        return view('examination::results.transcript');
    }

    public function rankings($exam)
    {
        return view('examination::results.rankings');
    }

    public function analytics()
    {
        return view('examination::results.analytics');
    }
}
