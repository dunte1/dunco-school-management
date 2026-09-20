@extends('layouts.app')

@section('title', 'CMS Settings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-cog me-2 text-primary"></i>CMS Settings</h1>
            <p class="text-muted mb-0">Manage public website settings and content</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.cms.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Hero Section --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-image me-2"></i>Hero Section</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Hero Title</label>
                        <input type="text" class="form-control" name="settings[hero_title]" value="{{ $settings['hero_title'] ?? '' }}" placeholder="Welcome to Our School">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hero Subtitle</label>
                        <input type="text" class="form-control" name="settings[hero_subtitle]" value="{{ $settings['hero_subtitle'] ?? '' }}" placeholder="Excellence in Education">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Hero Description</label>
                        <textarea class="form-control" name="settings[hero_description]" rows="3" placeholder="Brief description for the hero section...">{{ $settings['hero_description'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hero Image URL</label>
                        <input type="text" class="form-control" name="settings[hero_image]" value="{{ $settings['hero_image'] ?? '' }}" placeholder="images/hero-bg.jpg">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CTA Button Text</label>
                        <input type="text" class="form-control" name="settings[hero_cta_text]" value="{{ $settings['hero_cta_text'] ?? '' }}" placeholder="Enroll Now">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CTA Button URL</label>
                        <input type="text" class="form-control" name="settings[hero_cta_url]" value="{{ $settings['hero_cta_url'] ?? '' }}" placeholder="/admissions">
                    </div>
                </div>
            </div>
        </div>

        {{-- Trust Stats --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Trust Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Students Count</label>
                        <input type="text" class="form-control" name="settings[stat_students]" value="{{ $settings['stat_students'] ?? '' }}" placeholder="5000+">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Teachers Count</label>
                        <input type="text" class="form-control" name="settings[stat_teachers]" value="{{ $settings['stat_teachers'] ?? '' }}" placeholder="200+">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Years of Excellence</label>
                        <input type="text" class="form-control" name="settings[stat_years]" value="{{ $settings['stat_years'] ?? '' }}" placeholder="25+">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Success Rate</label>
                        <input type="text" class="form-control" name="settings[stat_success_rate]" value="{{ $settings['stat_success_rate'] ?? '' }}" placeholder="98%">
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="settings[contact_phone]" value="{{ $settings['contact_phone'] ?? '' }}" placeholder="+1 (555) 123-4567">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="settings[contact_email]" value="{{ $settings['contact_email'] ?? '' }}" placeholder="info@school.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="settings[contact_address]" value="{{ $settings['contact_address'] ?? '' }}" placeholder="123 Education St, City">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Working Hours</label>
                        <input type="text" class="form-control" name="settings[contact_hours]" value="{{ $settings['contact_hours'] ?? '' }}" placeholder="Mon-Fri: 8:00 AM - 4:00 PM">
                    </div>
                </div>
            </div>
        </div>

        {{-- Social Media --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-share-alt me-2"></i>Social Media</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label"><i class="fab fa-facebook me-1"></i> Facebook URL</label>
                        <input type="url" class="form-control" name="settings[social_facebook]" value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/school">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="fab fa-twitter me-1"></i> Twitter URL</label>
                        <input type="url" class="form-control" name="settings[social_twitter]" value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://twitter.com/school">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="fab fa-instagram me-1"></i> Instagram URL</label>
                        <input type="url" class="form-control" name="settings[social_instagram]" value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/school">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="fab fa-linkedin me-1"></i> LinkedIn URL</label>
                        <input type="url" class="form-control" name="settings[social_linkedin]" value="{{ $settings['social_linkedin'] ?? '' }}" placeholder="https://linkedin.com/school">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><i class="fab fa-youtube me-1"></i> YouTube URL</label>
                        <input type="url" class="form-control" name="settings[social_youtube]" value="{{ $settings['social_youtube'] ?? '' }}" placeholder="https://youtube.com/school">
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shoe-prints me-2"></i>Footer</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Copyright Text</label>
                        <input type="text" class="form-control" name="settings[footer_copyright]" value="{{ $settings['footer_copyright'] ?? '' }}" placeholder="© 2026 School Name. All rights reserved.">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Footer Description</label>
                        <textarea class="form-control" name="settings[footer_description]" rows="2" placeholder="Brief description for the footer...">{{ $settings['footer_description'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-1"></i> Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
