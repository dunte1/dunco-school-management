@extends('layouts.public')

@section('title', 'Contact Us - Dunco School Management System')
@section('meta_description', 'Get in touch with the Dunco SMS team. We are here to answer your questions and help you get started.')

@section('content')

<section class="page-hero">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-hero-content section-animate">
            <h1 class="page-hero-title">Get in Touch</h1>
            <p class="page-hero-subtitle">Have questions about Dunco SMS? We would love to hear from you. Send us a message and we will respond as soon as possible.</p>
        </div>
    </div>
</section>

<section class="contact-section">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="contact-grid">
            <div class="contact-info section-animate">
                <h2>Contact Information</h2>
                <p class="contact-info-text">Fill out the form and our team will get back to you within 24 hours.</p>

                <div class="contact-info-items">
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3>Email</h3>
                            <p>info@duncosms.com</p>
                            <p>support@duncosms.com</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <h3>Phone</h3>
                            <p>+1 (555) 123-4567</p>
                            <p>Mon - Fri, 8am - 6pm</p>
                        </div>
                    </div>
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h3>Office</h3>
                            <p>123 Education Lane</p>
                            <p>Nairobi, Kenya</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-form-wrapper section-animate">
                <form class="contact-form" id="contactForm" action="{{ route('public.contact.submit') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" class="form-input" required placeholder="John Doe" value="{{ old('name') }}">
                            @error('name')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-input" required placeholder="john@school.com" value="{{ old('email') }}">
                            @error('email')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-input" placeholder="+1 (555) 000-0000" value="{{ old('phone') }}">
                            @error('phone')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="organization" class="form-label">School/Organization</label>
                            <input type="text" id="organization" name="organization" class="form-input" placeholder="Greenfield Academy" value="{{ old('organization') }}">
                            @error('organization')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject" class="form-label">Subject <span class="required">*</span></label>
                        <select id="subject" name="subject" class="form-input form-select" required>
                            <option value="">Select a subject</option>
                            <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                            <option value="demo" {{ old('subject') == 'demo' ? 'selected' : '' }}>Request a Demo</option>
                            <option value="pricing" {{ old('subject') == 'pricing' ? 'selected' : '' }}>Pricing Information</option>
                            <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>Technical Support</option>
                            <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                            <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('subject')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="message" class="form-label">Message <span class="required">*</span></label>
                        <textarea id="message" name="message" class="form-input form-textarea" required rows="5" placeholder="Tell us how we can help you...">{{ old('message') }}</textarea>
                        @error('message')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-checkbox-label">
                            <input type="checkbox" name="consent" value="1" required>
                            <span>I agree to the <a href="#">Privacy Policy</a> and consent to being contacted regarding my inquiry.</span>
                        </label>
                        @error('consent')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="btn-primary-lg btn-full">
                        Send Message
                        <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
