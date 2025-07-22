<?php

namespace Modules\Portal\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\Models\Student;

class ApplicationController extends Controller
{
    public function showForm()
    {
        return view('portal::apply');
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'class_id' => 'required|integer',
            'parent_name' => 'required|string|max:255',
            'parent_contact' => 'required|string|max:255',
            'birth_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'passport_photo' => 'required|file|image',
        ]);
        $data['status'] = 'pending';
        if ($request->hasFile('birth_certificate')) {
            $data['birth_certificate'] = $request->file('birth_certificate')->store('applications/birth_certificates', 'public');
        }
        if ($request->hasFile('passport_photo')) {
            $data['passport'] = $request->file('passport_photo')->store('applications/passports', 'public');
        }
        $student = Student::create($data);
        return redirect()->route('portal.apply.form')->with('success', 'Application submitted! You will be contacted after review.');
    }
} 