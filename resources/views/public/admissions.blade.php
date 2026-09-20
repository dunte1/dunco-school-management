@extends('layouts.public')

@section('title', 'Apply for Admission - Dunco School Management System')
@section('meta_description', 'Apply for admission through Dunco SMS. Complete your application online for the current admission cycle.')

@section('content')

<section class="page-hero">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-hero-content section-animate">
            <h1 class="page-hero-title">Apply for Admission</h1>
            <p class="page-hero-subtitle">Complete your application online. Our streamlined process makes it easy to apply for admission to our school.</p>
        </div>
    </div>
</section>

<section class="admissions-section">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        @if(isset($activeAdmissionCycle) && $activeAdmissionCycle)
        <div class="admission-cycle-info glass-card section-animate">
            <div class="cycle-header">
                <div class="cycle-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h2 class="cycle-name">{{ $activeAdmissionCycle->name }}</h2>
                    <p class="cycle-dates">
                        {{ \Carbon\Carbon::parse($activeAdmissionCycle->start_date)->format('M d, Y') }}
                        &mdash;
                        {{ \Carbon\Carbon::parse($activeAdmissionCycle->end_date)->format('M d, Y') }}
                    </p>
                </div>
                <span class="cycle-status badge-success">Open</span>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-error section-animate">
            <div class="alert-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <strong>Please correct the following errors:</strong>
                <ul class="alert-errors">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="application-form-wrapper section-animate">
            <form id="admissionsForm" action="{{ route('public.admissions.submit') }}" method="POST" class="admissions-form">
                @csrf
                <input type="hidden" name="admission_cycle_id" value="{{ $activeAdmissionCycle->id }}">

                <div class="form-progress">
                    <div class="form-progress-bar" id="progressBar" style="width: 16.66%"></div>
                </div>

                <div class="form-steps-indicator">
                    <div class="step-dot active" data-step="1"><span>1</span><small>Personal</small></div>
                    <div class="step-dot" data-step="2"><span>2</span><small>Parent</small></div>
                    <div class="step-dot" data-step="3"><span>3</span><small>Academic</small></div>
                    <div class="step-dot" data-step="4"><span>4</span><small>Emergency</small></div>
                    <div class="step-dot" data-step="5"><span>5</span><small>Notes</small></div>
                    <div class="step-dot" data-step="6"><span>6</span><small>Review</small></div>
                </div>

                {{-- Step 1: Personal Information --}}
                <div class="form-step active" data-step="1">
                    <div class="form-step-header">
                        <h3>Personal Information</h3>
                        <p>Tell us about the applicant.</p>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name" class="form-label">First Name <span class="required">*</span></label>
                            <input type="text" id="first_name" name="first_name" class="form-input" required placeholder="Enter first name" value="{{ old('first_name') }}">
                            @error('first_name')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="form-label">Last Name <span class="required">*</span></label>
                            <input type="text" id="last_name" name="last_name" class="form-input" required placeholder="Enter last name" value="{{ old('last_name') }}">
                            @error('last_name')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-input" required placeholder="applicant@email.com" value="{{ old('email') }}">
                            @error('email')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" class="form-input" required placeholder="+1 (555) 000-0000" value="{{ old('phone') }}">
                            @error('phone')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_of_birth" class="form-label">Date of Birth <span class="required">*</span></label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-input" required value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="gender" class="form-label">Gender <span class="required">*</span></label>
                            <select id="gender" name="gender" class="form-input form-select" required>
                                <option value="">Select gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nationality" class="form-label">Nationality</label>
                        <input type="text" id="nationality" name="nationality" class="form-input" placeholder="e.g. Kenyan" value="{{ old('nationality') }}">
                        @error('nationality')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Step 2: Parent/Guardian --}}
                <div class="form-step" data-step="2">
                    <div class="form-step-header">
                        <h3>Parent / Guardian Information</h3>
                        <p>Provide details of the parent or guardian.</p>
                    </div>
                    <div class="form-group">
                        <label for="parent_name" class="form-label">Parent/Guardian Name <span class="required">*</span></label>
                        <input type="text" id="parent_name" name="parent_name" class="form-input" required placeholder="Full name of parent or guardian" value="{{ old('parent_name') }}">
                        @error('parent_name')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="parent_phone" class="form-label">Parent/Guardian Phone <span class="required">*</span></label>
                            <input type="tel" id="parent_phone" name="parent_phone" class="form-input" required placeholder="+1 (555) 000-0000" value="{{ old('parent_phone') }}">
                            @error('parent_phone')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="parent_email" class="form-label">Parent/Guardian Email</label>
                            <input type="email" id="parent_email" name="parent_email" class="form-input" placeholder="parent@email.com" value="{{ old('parent_email') }}">
                            @error('parent_email')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                {{-- Step 3: Academic History --}}
                <div class="form-step" data-step="3">
                    <div class="form-step-header">
                        <h3>Academic History</h3>
                        <p>Details about the applicant's previous schooling.</p>
                    </div>
                    <div class="form-group">
                        <label for="previous_school" class="form-label">Previous School</label>
                        <input type="text" id="previous_school" name="previous_school" class="form-input" placeholder="Name of previous school" value="{{ old('previous_school') }}">
                        @error('previous_school')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="last_grade_completed" class="form-label">Last Grade Completed</label>
                            <input type="text" id="last_grade_completed" name="last_grade_completed" class="form-input" placeholder="e.g. Grade 5" value="{{ old('last_grade_completed') }}">
                            @error('last_grade_completed')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="desired_class" class="form-label">Desired Class <span class="required">*</span></label>
                            <input type="text" id="desired_class" name="desired_class" class="form-input" required placeholder="e.g. Grade 6" value="{{ old('desired_class') }}">
                            @error('desired_class')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                {{-- Step 4: Emergency Contact --}}
                <div class="form-step" data-step="4">
                    <div class="form-step-header">
                        <h3>Emergency Contact</h3>
                        <p>Who should we contact in case of emergency?</p>
                    </div>
                    <div class="form-group">
                        <label for="emergency_contact_name" class="form-label">Emergency Contact Name <span class="required">*</span></label>
                        <input type="text" id="emergency_contact_name" name="emergency_contact_name" class="form-input" required placeholder="Full name" value="{{ old('emergency_contact_name') }}">
                        @error('emergency_contact_name')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone <span class="required">*</span></label>
                        <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" class="form-input" required placeholder="+1 (555) 000-0000" value="{{ old('emergency_contact_phone') }}">
                        @error('emergency_contact_phone')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Step 5: Additional Notes --}}
                <div class="form-step" data-step="5">
                    <div class="form-step-header">
                        <h3>Additional Notes</h3>
                        <p>Any special requirements or information we should know?</p>
                    </div>
                    <div class="form-group">
                        <label for="additional_notes" class="form-label">Additional Notes</label>
                        <textarea id="additional_notes" name="additional_notes" class="form-input form-textarea" rows="6" placeholder="Any allergies, medical conditions, special needs, or other information...">{{ old('additional_notes') }}</textarea>
                        @error('additional_notes')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Step 6: Review & Submit --}}
                <div class="form-step" data-step="6">
                    <div class="form-step-header">
                        <h3>Review & Submit</h3>
                        <p>Please review your information before submitting.</p>
                    </div>
                    <div class="review-section">
                        <div class="review-card glass-card">
                            <h4>Personal Information</h4>
                            <div class="review-grid">
                                <div><strong>Name:</strong> <span id="review-name">-</span></div>
                                <div><strong>Email:</strong> <span id="review-email">-</span></div>
                                <div><strong>Phone:</strong> <span id="review-phone">-</span></div>
                                <div><strong>Date of Birth:</strong> <span id="review-dob">-</span></div>
                                <div><strong>Gender:</strong> <span id="review-gender">-</span></div>
                                <div><strong>Nationality:</strong> <span id="review-nationality">-</span></div>
                            </div>
                        </div>
                        <div class="review-card glass-card">
                            <h4>Parent/Guardian</h4>
                            <div class="review-grid">
                                <div><strong>Name:</strong> <span id="review-parent-name">-</span></div>
                                <div><strong>Phone:</strong> <span id="review-parent-phone">-</span></div>
                                <div><strong>Email:</strong> <span id="review-parent-email">-</span></div>
                            </div>
                        </div>
                        <div class="review-card glass-card">
                            <h4>Academic History</h4>
                            <div class="review-grid">
                                <div><strong>Previous School:</strong> <span id="review-prev-school">-</span></div>
                                <div><strong>Last Grade:</strong> <span id="review-last-grade">-</span></div>
                                <div><strong>Desired Class:</strong> <span id="review-desired-class">-</span></div>
                            </div>
                        </div>
                        <div class="review-card glass-card">
                            <h4>Emergency Contact</h4>
                            <div class="review-grid">
                                <div><strong>Name:</strong> <span id="review-emergency-name">-</span></div>
                                <div><strong>Phone:</strong> <span id="review-emergency-phone">-</span></div>
                            </div>
                        </div>
                        <div class="review-card glass-card">
                            <h4>Additional Notes</h4>
                            <p id="review-notes" class="review-notes">None provided</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-checkbox-label">
                            <input type="checkbox" name="consent" value="1" required>
                            <span>I confirm that the information provided is accurate and I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</span>
                        </label>
                        @error('consent')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-navigation">
                    <button type="button" class="btn-ghost-lg form-btn-prev" id="prevBtn" style="display: none;">
                        <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                        Previous
                    </button>
                    <button type="button" class="btn-primary-lg form-btn-next" id="nextBtn">
                        Next
                        <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </button>
                    <button type="submit" class="btn-primary-lg form-btn-submit" id="submitBtn" style="display: none;">
                        Submit Application
                        <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </div>
            </form>
        </div>

        @else
        <div class="no-admission glass-card section-animate">
            <div class="no-admission-icon">
                <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2>No Admissions Currently Open</h2>
            <p>There are no active admission cycles at the moment. Please check back later or contact our admissions office for more information.</p>
            <div class="no-admission-actions">
                <a href="{{ route('public.contact') }}" class="btn-primary-lg">
                    Contact Admissions
                    <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="/" class="btn-ghost-lg">Back to Home</a>
            </div>
        </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('admissionsForm');
    if (!form) return;

    const steps = document.querySelectorAll('.form-step');
    const stepDots = document.querySelectorAll('.step-dot');
    const progressBar = document.getElementById('progressBar');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    let currentStep = 1;
    const totalSteps = steps.length;

    function showStep(step) {
        steps.forEach(s => s.classList.remove('active'));
        stepDots.forEach(d => d.classList.remove('active'));

        const targetStep = document.querySelector(`.form-step[data-step="${step}"]`);
        if (targetStep) targetStep.classList.add('active');

        const targetDot = document.querySelector(`.step-dot[data-step="${step}"]`);
        if (targetDot) targetDot.classList.add('active');

        // Mark completed dots
        stepDots.forEach(d => {
            if (parseInt(d.dataset.step) < step) d.classList.add('completed');
        });

        progressBar.style.width = ((step / totalSteps) * 100) + '%';

        prevBtn.style.display = step === 1 ? 'none' : 'inline-flex';
        nextBtn.style.display = step === totalSteps ? 'none' : 'inline-flex';
        submitBtn.style.display = step === totalSteps ? 'inline-flex' : 'none';

        if (step === totalSteps) populateReview();

        targetStep.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function validateStep(step) {
        const stepEl = document.querySelector(`.form-step[data-step="${step}"]`);
        const required = stepEl.querySelectorAll('[required]');
        let valid = true;
        required.forEach(input => {
            if (!input.value.trim()) {
                input.style.borderColor = '#ef4444';
                valid = false;
            } else {
                input.style.borderColor = '';
            }
        });
        return valid;
    }

    function populateReview() {
        document.getElementById('review-name').textContent =
            (document.getElementById('first_name').value + ' ' + document.getElementById('last_name').value).trim() || '-';
        document.getElementById('review-email').textContent = document.getElementById('email').value || '-';
        document.getElementById('review-phone').textContent = document.getElementById('phone').value || '-';
        document.getElementById('review-dob').textContent = document.getElementById('date_of_birth').value || '-';
        document.getElementById('review-gender').textContent = document.getElementById('gender').value || '-';
        document.getElementById('review-nationality').textContent = document.getElementById('nationality').value || '-';
        document.getElementById('review-parent-name').textContent = document.getElementById('parent_name').value || '-';
        document.getElementById('review-parent-phone').textContent = document.getElementById('parent_phone').value || '-';
        document.getElementById('review-parent-email').textContent = document.getElementById('parent_email').value || '-';
        document.getElementById('review-prev-school').textContent = document.getElementById('previous_school').value || '-';
        document.getElementById('review-last-grade').textContent = document.getElementById('last_grade_completed').value || '-';
        document.getElementById('review-desired-class').textContent = document.getElementById('desired_class').value || '-';
        document.getElementById('review-emergency-name').textContent = document.getElementById('emergency_contact_name').value || '-';
        document.getElementById('review-emergency-phone').textContent = document.getElementById('emergency_contact_phone').value || '-';
        document.getElementById('review-notes').textContent = document.getElementById('additional_notes').value || 'None provided';
    }

    nextBtn.addEventListener('click', function() {
        if (validateStep(currentStep) && currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });
});
</script>
@endpush
