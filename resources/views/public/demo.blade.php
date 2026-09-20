@extends('layouts.public')

@section('title', 'Request a Demo - Dunco School Management System')
@section('meta_description', 'Request a personalized demo of Dunco SMS. See how our platform can transform your school administration.')

@section('content')

<section class="page-hero page-hero-dark">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-hero-content section-animate">
            <h1 class="page-hero-title">Request a Demo</h1>
            <p class="page-hero-subtitle">See Dunco SMS in action. Schedule a personalized demo with our team and discover how we can help your school.</p>
        </div>
    </div>
</section>

<section class="demo-section">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="demo-grid">
            <div class="demo-features section-animate">
                <h2>What to Expect</h2>
                <div class="demo-feature-list">
                    <div class="demo-feature-item">
                        <div class="demo-feature-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3>Live Walkthrough</h3>
                            <p>A 30-minute personalized demo of the platform features relevant to your school.</p>
                        </div>
                    </div>
                    <div class="demo-feature-item">
                        <div class="demo-feature-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <div>
                            <h3>Q&A Session</h3>
                            <p>Ask any questions about features, pricing, implementation, or support.</p>
                        </div>
                    </div>
                    <div class="demo-feature-item">
                        <div class="demo-feature-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <div>
                            <h3>Custom Proposal</h3>
                            <p>Receive a tailored proposal with pricing based on your school's needs.</p>
                        </div>
                    </div>
                    <div class="demo-feature-item">
                        <div class="demo-feature-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        </div>
                        <div>
                            <h3>No Commitment</h3>
                            <p>The demo is completely free with no obligation to purchase.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="demo-form-wrapper section-animate">
                <form class="contact-form" id="demoForm" action="{ route('public.demo.submit') }" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Your Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-input" required placeholder="Full name" value="{ old('name') }">
                        @error('name')<span class="form-error">{ $message }</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="institution" class="form-label">Institution Name <span class="required">*</span></label>
                        <input type="text" id="institution" name="institution" class="form-input" required placeholder="School or institution name" value="{ old('institution') }">
                        @error('institution')<span class="form-error">{ $message }</span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email" class="form-label">Email <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-input" required placeholder="you@school.com" value="{ old('email') }">
                            @error('email')<span class="form-error">{ $message }</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" id="phone" name="phone" class="form-input" placeholder="+1 (555) 000-0000" value="{ old('phone') }">
                            @error('phone')<span class="form-error">{ $message }</span>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="students" class="form-label">Number of Students</label>
                            <select id="students" name="number_of_students" class="form-input form-select">
                                <option value="">Select range</option>
                                <option value="1-100">1 - 100</option>
                                <option value="100-500">100 - 500</option>
                                <option value="500-1000">500 - 1,000</option>
                                <option value="1000-5000">1,000 - 5,000</option>
                                <option value="5000+">5,000+</option>
                            </select>
                            @error('number_of_students')<span class="form-error">{ $message }</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="current_system" class="form-label">Current System</label>
                            <input type="text" id="current_system" name="current_system" class="form-input" placeholder="e.g., Paper-based, Excel" value="{ old('current_system') }">
                            @error('current_system')<span class="form-error">{ $message }</span>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Interested Modules</label>
                        <div class="checkbox-grid">
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="Academic"> Academic</label>
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="Examination"> Examination</label>
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="Finance"> Finance</label>
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="Attendance"> Attendance</label>
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="Timetable"> Timetable</label>
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="Library"> Library</label>
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="Hostel"> Hostel</label>
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="Transport"> Transport</label>
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="HR"> HR</label>
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="Communication"> Communication</label>
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="Portal"> Portal</label>
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="Documents"> Documents</label>
                            <label class="checkbox-label"><input type="checkbox" name="modules[]" value="AI ChatBot"> AI ChatBot</label>
                        </div>
                        @error('modules')<span class="form-error">{ $message }</span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="preferred_date" class="form-label">Preferred Date</label>
                            <input type="date" id="preferred_date" name="preferred_date" class="form-input" value="{ old('preferred_date') }">
                            @error('preferred_date')<span class="form-error">{ $message }</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="preferred_time" class="form-label">Preferred Time</label>
                            <select id="preferred_time" name="preferred_time" class="form-input form-select">
                                <option value="">Select time</option>
                                <option value="09:00">9:00 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="13:00">1:00 PM</option>
                                <option value="14:00">2:00 PM</option>
                                <option value="15:00">3:00 PM</option>
                                <option value="16:00">4:00 PM</option>
                            </select>
                            @error('preferred_time')<span class="form-error">{ $message }</span>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="form-label">Additional Message</label>
                        <textarea id="message" name="message" class="form-input form-textarea" rows="4" placeholder="Tell us about your specific needs...">{ old('message') }</textarea>
                        @error('message')<span class="form-error">{ $message }</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-checkbox-label">
                            <input type="checkbox" name="consent" value="1" required>
                            <span>I agree to the <a href="#">Privacy Policy</a> and consent to being contacted about my demo request.</span>
                        </label>
                        @error('consent')<span class="form-error">{ $message }</span>@enderror
                    </div>
                    <button type="submit" class="btn-primary-lg btn-full">
                        Schedule My Demo
                        <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
