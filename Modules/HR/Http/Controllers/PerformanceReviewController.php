<?php

namespace Modules\HR\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\HR\Models\PerformanceReview;
use Modules\HR\Models\Staff;
use Illuminate\Http\Request;

class PerformanceReviewController extends Controller
{
    public function index()
    {
        $reviews = PerformanceReview::with(['staff', 'reviewer'])->latest()->paginate(10);
        return view('hr::performance_reviews.index', compact('reviews'));
    }

    public function create()
    {
        $staff = Staff::all();
        return view('hr::performance_reviews.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'reviewer_id' => 'required|exists:staff,id',
            'period' => 'required|string|max:255',
            'score' => 'required|numeric|min:1|max:10',
            'comments' => 'nullable|string',
            'review_date' => 'required|date',
        ]);
        PerformanceReview::create($request->all());
        return redirect()->route('hr.performance_reviews.index')->with('success', 'Performance review created successfully.');
    }

    public function edit($id)
    {
        $review = PerformanceReview::findOrFail($id);
        $staff = Staff::all();
        return view('hr::performance_reviews.edit', compact('review', 'staff'));
    }

    public function update(Request $request, $id)
    {
        $review = PerformanceReview::findOrFail($id);
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'reviewer_id' => 'required|exists:staff,id',
            'period' => 'required|string|max:255',
            'score' => 'required|numeric|min:1|max:10',
            'comments' => 'nullable|string',
            'review_date' => 'required|date',
        ]);
        $review->update($request->all());
        return redirect()->route('hr.performance_reviews.index')->with('success', 'Performance review updated successfully.');
    }

    public function destroy($id)
    {
        $review = PerformanceReview::findOrFail($id);
        $review->delete();
        return redirect()->route('hr.performance_reviews.index')->with('success', 'Performance review deleted successfully.');
    }

    public function show($id)
    {
        $review = PerformanceReview::with(['staff', 'reviewer'])->findOrFail($id);
        return view('hr::performance_reviews.show', compact('review'));
    }
} 