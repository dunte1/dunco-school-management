<?php

namespace App\Http\Controllers;

use App\Models\AdmissionApplication;
use App\Models\AdmissionApplicationLog;
use App\Models\AdmissionCycle;
use App\Models\AdmissionDocumentType;
use App\Models\AdmissionField;
use App\Models\AdmissionSection;
use App\Models\ContactEnquiry;
use App\Models\DemoRequest;
use App\Models\Faq;
use App\Models\Lead;
use App\Models\PricingPlan;
use App\Models\PublicSetting;
use App\Models\PublicModule;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublicController extends Controller
{
    public function welcome()
    {
        $testimonials = Testimonial::active()->ordered()->get();
        $faqs = Faq::active()->ordered()->get();
        $pricingPlans = PricingPlan::active()->ordered()->with('features')->get();
        $settings = PublicSetting::getAll();
        $activeAdmissionCycle = AdmissionCycle::active()->open()->first();
        $publicModules = PublicModule::active()->ordered()->get();

        return view('welcome', compact('testimonials', 'faqs', 'pricingPlans', 'settings', 'activeAdmissionCycle', 'publicModules'));
    }

    public function features()
    {
        $pricingPlans = PricingPlan::active()->ordered()->with('features')->get();
        $settings = PublicSetting::getAll();

        return view('public.features', compact('pricingPlans', 'settings'));
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'organization' => 'nullable|string|max:255',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:5000',
            'consent' => 'required|accepted',
        ]);

        unset($validated['consent']);

        $reference = 'ENQ-' . strtoupper(uniqid());

        ContactEnquiry::create(array_merge($validated, [
            'reference' => $reference,
            'status' => 'new',
        ]));

        session()->flash('success', 'Thank you for contacting us! Reference: ' . $reference . '. We will get back to you within 24 hours.');

        return redirect()->route('public.contact');
    }

    public function demo()
    {
        return view('public.demo');
    }

    public function demoSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'number_of_students' => 'nullable|string|max:50',
            'current_system' => 'nullable|string|max:255',
            'modules' => 'nullable|array',
            'modules.*' => 'string|max:100',
            'preferred_date' => 'nullable|date|after:today',
            'preferred_time' => 'nullable|string|max:20',
            'message' => 'nullable|string|max:5000',
            'consent' => 'required|accepted',
        ]);

        unset($validated['consent']);

        $reference = 'DEMO-' . strtoupper(uniqid());

        DemoRequest::create(array_merge($validated, [
            'reference' => $reference,
            'status' => 'new',
        ]));

        Lead::create([
            'source' => 'demo_request',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'organization' => $validated['institution'],
            'status' => 'new',
            'notes' => 'Demo request ' . $reference . ' for ' . $validated['institution'],
        ]);

        session()->flash('success', 'Your demo request has been submitted! Reference: ' . $reference . '. Our team will contact you within 24 hours to confirm your demo schedule.');

        return redirect()->route('public.demo');
    }

    public function moduleDetail($slug)
    {
        $module = PublicModule::where('slug', $slug)->active()->firstOrFail();

        return view('public.module-detail', compact('module'));
    }

    public function admissions()
    {
        $activeAdmissionCycle = AdmissionCycle::active()->open()->first();
        $sections = AdmissionSection::active()->ordered()->with('fields')->get();
        $documentTypes = AdmissionDocumentType::active()->ordered()->get();

        return view('public.admissions', compact('activeAdmissionCycle', 'sections', 'documentTypes'));
    }

    public function admissionsSubmit(Request $request)
    {
        $activeCycle = AdmissionCycle::active()->open()->first();

        if (!$activeCycle) {
            return redirect()->route('public.admissions')
                ->with('error', 'No active admission cycle is currently accepting applications.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|string|in:Male,Female,Other',
            'nationality' => 'nullable|string|max:100',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:50',
            'parent_email' => 'nullable|email|max:255',
            'previous_school' => 'nullable|string|max:255',
            'last_grade_completed' => 'nullable|string|max:100',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:50',
            'additional_notes' => 'nullable|string|max:5000',
            'desired_class' => 'nullable|string|max:100',
        ]);

        $applicationNumber = AdmissionApplication::generateApplicationNumber();

        $application = DB::transaction(function () use ($validated, $activeCycle, $applicationNumber) {
            $application = AdmissionApplication::create([
                'application_number' => $applicationNumber,
                'admission_cycle_id' => $activeCycle->id,
                'status' => 'submitted',
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'nationality' => $validated['nationality'] ?? null,
                'parent_data' => [
                    'name' => $validated['parent_name'],
                    'phone' => $validated['parent_phone'],
                    'email' => $validated['parent_email'] ?? null,
                ],
                'academic_data' => [
                    'previous_school' => $validated['previous_school'] ?? null,
                    'last_grade_completed' => $validated['last_grade_completed'] ?? null,
                ],
                'emergency_data' => [
                    'contact_name' => $validated['emergency_contact_name'],
                    'contact_phone' => $validated['emergency_contact_phone'],
                ],
                'field_values' => collect($validated)->except([
                    'consent', 'desired_class', 'parent_name', 'parent_phone', 'parent_email',
                    'previous_school', 'last_grade_completed',
                    'emergency_contact_name', 'emergency_contact_phone',
                ])->toArray(),
                'desired_class' => $validated['desired_class'] ?? null,
                'notes' => $validated['additional_notes'] ?? null,
            ]);

            AdmissionApplicationLog::create([
                'admission_application_id' => $application->id,
                'action' => 'submitted',
                'old_status' => 'draft',
                'new_status' => 'submitted',
                'details' => 'Application submitted via public form.',
            ]);

            return $application;
        });

        Log::info('Admission application submitted', [
            'application_number' => $applicationNumber,
            'cycle' => $activeCycle->name,
            'applicant' => $validated['first_name'] . ' ' . $validated['last_name'],
        ]);

        return redirect()->route('public.admissions')
            ->with('success', 'Your application has been submitted successfully! Application Number: ' . $applicationNumber . '. We will review your application and contact you soon.');
    }
}
