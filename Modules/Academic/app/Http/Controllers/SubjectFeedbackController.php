<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\Subject;
use Modules\Academic\Models\SubjectFeedback;
use Illuminate\Support\Facades\Auth;

class SubjectFeedbackController extends Controller
{
    // List feedback for a subject
    public function index($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        $feedback = $subject->feedback()->with('user')->latest()->get();
        return response()->json(['feedback' => $feedback]);
    }

    // Submit feedback for a subject
    public function store(Request $request, $subjectId)
    {
        $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $feedback = SubjectFeedback::create([
            'subject_id' => $subjectId,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['success' => true, 'feedback' => $feedback]);
    }

    // Delete feedback (admin or owner)
    public function destroy($subjectId, $feedbackId)
    {
        $feedback = SubjectFeedback::where('subject_id', $subjectId)->findOrFail($feedbackId);
        if (Auth::id() !== $feedback->user_id && !Auth::user()->hasRole('admin')) {
            abort(403);
        }
        $feedback->delete();
        return response()->json(['success' => true, 'message' => 'Feedback deleted.']);
    }
}
