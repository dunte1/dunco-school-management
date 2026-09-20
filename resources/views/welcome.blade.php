@extends('layouts.public')

@section('title', 'Dunco School Management System - Modern School Management Made Simple')
@section('meta_description', 'A comprehensive, all-in-one platform to manage students, staff, academics, finances, and everything in between.')

@section('content')

@if($activeAdmissionCycle ?? null)
<div class="announcement-bar">
    <div class="container mx-auto px-4 flex items-center justify-center gap-3">
        <span class="announcement-badge">New</span>
        <p class="text-sm font-medium">Admissions open for {{ $activeAdmissionCycle->name }}</p>
        <a href="{{ route('public.admissions') }}" class="announcement-link">Apply Now &rarr;</a>
    </div>
</div>
@endif

<section class="hero-section" id="hero">
    <div class="hero-bg">
        <div class="hero-gradient-orb hero-orb-1"></div>
        <div class="hero-gradient-orb hero-orb-2"></div>
        <div class="hero-gradient-orb hero-orb-3"></div>
        <div class="hero-grid-pattern"></div>
    </div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="hero-grid">
            <div class="hero-content section-animate">
                <div class="hero-badge animate-fade-up stagger-1">
                    <span class="hero-badge-dot"></span>
                    Trusted by leading institutions
                </div>
                <h1 class="hero-title animate-fade-up stagger-2">
                    {{ $settings['hero_title'] ?? 'Transform the Way Your School Works' }}
                </h1>
                <p class="hero-subtitle animate-fade-up stagger-3">
                    {{ $settings['hero_subtitle'] ?? 'A comprehensive, all-in-one platform to manage students, staff, academics, finances, and everything in between. Built for the modern educational institution.' }}
                </p>
                <div class="hero-buttons animate-fade-up stagger-4">
                    @guest
                        <a href="{{ route('public.demo') }}" class="btn-primary-lg">
                            Request a Demo
                            <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="#how-it-works" class="btn-ghost-lg">
                            Explore the Platform
                            <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-primary-lg">
                            Go to Dashboard
                            <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @endauth
                </div>
                <div class="hero-stats animate-fade-up stagger-5">
                    <div class="hero-stat-item">
                        <span class="hero-stat-number counter" data-target="17">0</span><span class="hero-stat-suffix">+</span>
                        <span class="hero-stat-label">Modules</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat-item">
                        <span class="hero-stat-number counter" data-target="100">0</span><span class="hero-stat-suffix">+</span>
                        <span class="hero-stat-label">Features</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat-item">
                        <span class="hero-stat-number counter" data-target="99">0</span><span class="hero-stat-suffix">%</span>
                        <span class="hero-stat-label">Uptime</span>
                    </div>
                </div>
            </div>
            <div class="hero-visual animate-fade-left stagger-3">
                <div class="hero-card glass-card">
                    <div class="hero-card-header">
                        <div class="hero-card-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3>School Dashboard</h3>
                    </div>
                    <div class="hero-card-row"><span class="hero-card-label">Total Students</span><span class="hero-card-value">2,450</span></div>
                    <div class="hero-card-row"><span class="hero-card-label">Attendance Rate</span><span class="hero-card-value badge-success">94.2%</span></div>
                    <div class="hero-card-row"><span class="hero-card-label">Fee Collection</span><span class="hero-card-value badge-info">$128,400</span></div>
                    <div class="hero-card-row"><span class="hero-card-label">Active Classes</span><span class="hero-card-value badge-warning">48</span></div>
                    <div class="hero-card-row"><span class="hero-card-label">Staff Members</span><span class="hero-card-value">186</span></div>
                </div>
                <div class="hero-floating-card hero-float-1 glass-card">
                    <div class="floating-card-icon text-green-500"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                    <span class="text-xs font-semibold text-gray-700">Payment Received</span>
                </div>
                <div class="hero-floating-card hero-float-2 glass-card">
                    <div class="floating-card-icon text-blue-500"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></div>
                    <span class="text-xs font-semibold text-gray-700">New Notification</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="trust-section" id="trust">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="trust-grid section-animate">
            <div class="trust-item">
                <div class="trust-icon"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                <span class="trust-number counter" data-target="17">0</span>
                <span class="trust-label">Modules</span>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                <span class="trust-number counter" data-target="2450">0</span><span class="trust-plus">+</span>
                <span class="trust-label">Students Managed</span>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                <span class="trust-number counter" data-target="50">0</span><span class="trust-plus">+</span>
                <span class="trust-label">Institutions</span>
            </div>
            <div class="trust-item">
                <div class="trust-icon"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                <span class="trust-number counter" data-target="99">0</span><span class="trust-suffix">%</span>
                <span class="trust-label">Uptime SLA</span>
            </div>
        </div>
    </div>
</section>

<section class="problem-solution-section" id="problem-solution">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header section-animate">
            <span class="section-tag">The Challenge</span>
            <h2 class="section-title">Schools Face Real Problems Every Day</h2>
            <p class="section-subtitle">Manual processes, fragmented systems, and communication gaps make school administration unnecessarily difficult.</p>
        </div>
        <div class="problems-grid section-animate">
            <div class="problem-card">
                <div class="problem-icon"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                <h3>Manual Paperwork</h3>
                <p>Endless forms, filing cabinets, and paper trails that slow down every process.</p>
            </div>
            <div class="problem-card">
                <div class="problem-icon"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg></div>
                <h3>Fragmented Records</h3>
                <p>Student data scattered across spreadsheets, notebooks, and disconnected systems.</p>
            </div>
            <div class="problem-card">
                <div class="problem-icon"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></div>
                <h3>Poor Communication</h3>
                <p>No efficient way to reach parents, students, and staff simultaneously.</p>
            </div>
            <div class="problem-card">
                <div class="problem-icon"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <h3>Fee Management</h3>
                <p>Tracking payments, generating invoices, and managing collections is exhausting.</p>
            </div>
            <div class="problem-card">
                <div class="problem-icon"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
                <h3>Reporting Challenges</h3>
                <p>Generating meaningful reports requires hours of manual data compilation.</p>
            </div>
            <div class="problem-card">
                <div class="problem-icon"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <h3>Attendance Tracking</h3>
                <p>Roll calls, paper registers, and no real-time visibility into attendance patterns.</p>
            </div>
        </div>
        <div class="problem-solution-arrow section-animate">
            <div class="solution-transition">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                <span>There is a better way</span>
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </div>
        </div>
        <div class="section-header section-animate" style="margin-top: 4rem;">
            <span class="section-tag section-tag-green">The Solution</span>
            <h2 class="section-title">Dunco SMS: One Platform, Everything Connected</h2>
            <p class="section-subtitle">Replace dozens of disconnected tools with a single, powerful platform that handles every aspect of school management.</p>
        </div>
    </div>
</section>

<section class="features-section" id="features">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header section-animate">
            <span class="section-tag">Why Dunco SMS</span>
            <h2 class="section-title">Everything You Need to Run Your School</h2>
            <p class="section-subtitle">From student admission to graduation, our platform covers every aspect of school administration.</p>
        </div>
        <div class="features-grid section-animate">
            <div class="feature-card">
                <div class="feature-icon fi-blue"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
                <h3>Real-Time Analytics</h3>
                <p>Track student performance, attendance, and financial health with live dashboards and interactive charts.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon fi-purple"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg></div>
                <h3>Mobile Ready</h3>
                <p>Full mobile experience for students, parents, and teachers with push notifications.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon fi-green"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div>
                <h3>Secure & Private</h3>
                <p>Enterprise-grade security with role-based access control, encryption, and audit logging.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon fi-orange"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <h3>Fee Management</h3>
                <p>Automated billing, invoice generation, online payments, and financial reporting.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon fi-red"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                <h3>Online Examinations</h3>
                <p>Create, administer, and grade exams online with proctoring and analytics.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon fi-teal"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg></div>
                <h3>AI-Powered Assistant</h3>
                <p>Built-in AI chatbot for student queries, tutoring, and administrative automation.</p>
            </div>
        </div>
    </div>
</section>

<section class="modules-section" id="modules">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header section-animate">
            <span class="section-tag">Modules</span>
            <h2 class="section-title">{{ $publicModules->count() }} Powerful Modules</h2>
            <p class="section-subtitle">Each module is fully integrated and works seamlessly with the others.</p>
        </div>
        <div class="modules-grid section-animate">
            @forelse($publicModules as $module)
            <a href="/features/{{ $module->slug }}" class="module-card">
                <div class="module-icon">{!! $module->icon !!}</div>
                <h3>{{ $module->name }}</h3>
                <p>{{ $module->short_description }}</p>
            </a>
            @empty
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
                <h3>Academic</h3>
                <p>Students, classes, subjects, enrollment</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                <h3>Examination</h3>
                <p>Online exams, proctoring, results</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <h3>Finance</h3>
                <p>Fees, payments, invoicing, reports</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                <h3>Attendance</h3>
                <p>Daily tracking, QR, biometric</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <h3>Timetable</h3>
                <p>Schedule generation, conflict detection</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
                <h3>Library</h3>
                <p>Books, borrowing, digital library</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div>
                <h3>Hostel</h3>
                <p>Rooms, allocation, wardens</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 4h8m-6 4h4M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg></div>
                <h3>Transport</h3>
                <p>Vehicles, routes, drivers, trips</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                <h3>HR & Staff</h3>
                <p>Employees, payroll, leave, contracts</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></div>
                <h3>Communication</h3>
                <p>Messaging, SMS, email, broadcast</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div>
                <h3>Portal</h3>
                <p>Student, parent, teacher dashboards</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg></div>
                <h3>Documents</h3>
                <p>Upload, manage, categorize files</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></div>
                <h3>Notifications</h3>
                <p>Push, email, SMS, templates</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg></div>
                <h3>AI ChatBot</h3>
                <p>Gemini/OpenAI integration</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                <h3>Settings</h3>
                <p>Global config, branding, backups</p>
            </div>
            <div class="module-card">
                <div class="module-icon"><svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg></div>
                <h3>Core / Admin</h3>
                <p>Users, roles, permissions, audit</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="stats-bg">
        <div class="stats-orb stats-orb-1"></div>
        <div class="stats-orb stats-orb-2"></div>
    </div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="stats-grid section-animate">
            <div class="stat-item">
                <span class="stat-number counter" data-target="17">0</span>
                <span class="stat-label">Modules</span>
            </div>
            <div class="stat-item">
                <span class="stat-number counter" data-target="100">0</span><span class="stat-suffix">+</span>
                <span class="stat-label">Features</span>
            </div>
            <div class="stat-item">
                <span class="stat-number counter" data-target="70">0</span><span class="stat-suffix">+</span>
                <span class="stat-label">API Endpoints</span>
            </div>
            <div class="stat-item">
                <span class="stat-number counter" data-target="50">0</span><span class="stat-suffix">+</span>
                <span class="stat-label">Tests Passing</span>
            </div>
        </div>
    </div>
</section>

<section class="how-it-works-section" id="how-it-works">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header section-animate">
            <span class="section-tag">How It Works</span>
            <h2 class="section-title">Get Started in Three Simple Steps</h2>
            <p class="section-subtitle">From sign-up to full deployment, we make the process seamless.</p>
        </div>
        <div class="steps-grid section-animate">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Request a Demo</h3>
                <p>Tell us about your school and we will set up a personalized demo to show how Dunco SMS fits your needs.</p>
            </div>
            <div class="step-connector"><svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Configure Your School</h3>
                <p>We help you set up classes, subjects, staff, fee structures, and import existing student data.</p>
            </div>
            <div class="step-connector"><svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Go Live</h3>
                <p>Start using Dunco SMS with your staff, students, and parents. We provide ongoing support and training.</p>
            </div>
        </div>
    </div>
</section>

<section class="roles-section" id="roles">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header section-animate">
            <span class="section-tag">Built for Everyone</span>
            <h2 class="section-title">Designed for Every Role in Your School</h2>
            <p class="section-subtitle">Whether you are an administrator, teacher, parent, or student, Dunco SMS has something for you.</p>
        </div>
        <div class="roles-grid section-animate">
            <div class="role-card role-admin">
                <div class="role-card-top">
                    <div class="role-icon">
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3>Administrators</h3>
                </div>
                <ul class="role-benefits">
                    <li>Full school overview dashboard</li>
                    <li>Complete student and staff management</li>
                    <li>Financial reports and analytics</li>
                    <li>Automated fee collection</li>
                    <li>Audit logging and compliance</li>
                </ul>
            </div>
            <div class="role-card role-teacher">
                <div class="role-card-top">
                    <div class="role-icon">
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3>Teachers</h3>
                </div>
                <ul class="role-benefits">
                    <li>Easy attendance marking</li>
                    <li>Grade and result management</li>
                    <li>Timetable and schedule access</li>
                    <li>Student performance tracking</li>
                    <li>Parent communication tools</li>
                </ul>
            </div>
            <div class="role-card role-parent">
                <div class="role-card-top">
                    <div class="role-icon">
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3>Parents</h3>
                </div>
                <ul class="role-benefits">
                    <li>Real-time child attendance</li>
                    <li>Exam results and progress</li>
                    <li>Fee balance and payment</li>
                    <li>Direct teacher communication</li>
                    <li>School announcements</li>
                </ul>
            </div>
            <div class="role-card role-student">
                <div class="role-card-top">
                    <div class="role-icon">
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3>Students</h3>
                </div>
                <ul class="role-benefits">
                    <li>Access to timetable and schedule</li>
                    <li>View exam results</li>
                    <li>Submit assignments</li>
                    <li>Library access and borrowing</li>
                    <li>AI-powered study assistant</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="testimonials-section" id="testimonials">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header section-animate">
            <span class="section-tag">Testimonials</span>
            <h2 class="section-title">Trusted by Educators</h2>
            <p class="section-subtitle">Hear from schools that have transformed their operations with Dunco SMS.</p>
        </div>
        @if($testimonials->count())
        <div class="testimonials-slider section-animate" id="testimonialSlider">
            <div class="testimonials-track">
                @foreach($testimonials as $index => $testimonial)
                <div class="testimonial-card {{ $loop->first ? 'is-active' : '' }}">
                    <div class="testimonial-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= ($testimonial->rating ?? 5))
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @else
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @endif
                        @endfor
                    </div>
                    <p class="testimonial-text">{{ $testimonial->content }}</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">{{ strtoupper(substr($testimonial->name, 0, 2)) }}</div>
                        <div>
                            <div class="testimonial-name">{{ $testimonial->name }}</div>
                            <div class="testimonial-role">{{ $testimonial->position }}{{ $testimonial->institution ? ', ' . $testimonial->institution : '' }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="testimonial-dots">
                @foreach($testimonials as $i => $t)
                    <button class="testimonial-dot {{ $loop->first ? 'is-active' : '' }}" data-index="{{ $i }}" aria-label="Testimonial {{ $i + 1 }}"></button>
                @endforeach
            </div>
        </div>
        @else
        <div class="testimonials-slider section-animate" id="testimonialSlider">
            <div class="testimonials-track">
                <div class="testimonial-card is-active">
                    <div class="testimonial-stars">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <p class="testimonial-text">Dunco SMS has completely transformed how we manage our school. The attendance tracking alone has saved us hours every week. The real-time dashboard gives us visibility we never had before.</p>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">JM</div>
                        <div>
                            <div class="testimonial-name">James Mwangi</div>
                            <div class="testimonial-role">Principal, Greenfield Academy</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-dots">
                <button class="testimonial-dot is-active" data-index="0" aria-label="Testimonial 1"></button>
            </div>
        </div>
        @endif
    </div>
</section>

<section class="faq-section" id="faq">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header section-animate">
            <span class="section-tag">FAQ</span>
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-subtitle">Find answers to common questions about Dunco SMS.</p>
        </div>
        <div class="faq-list section-animate">
            @forelse($faqs as $faq)
            <div class="accordion-item">
                <button class="accordion-trigger" aria-expanded="false">
                    <span>{{ $faq->question }}</span>
                    <svg class="accordion-chevron" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="accordion-content">
                    <p>{{ $faq->answer }}</p>
                </div>
            </div>
            @empty
            <div class="accordion-item">
                <button class="accordion-trigger" aria-expanded="false">
                    <span>What is Dunco SMS?</span>
                    <svg class="accordion-chevron" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="accordion-content">
                    <p>Dunco School Management System (Dunco SMS) is a comprehensive, all-in-one platform designed to manage every aspect of school administration including students, staff, academics, finances, attendance, examinations, and more.</p>
                </div>
            </div>
            <div class="accordion-item">
                <button class="accordion-trigger" aria-expanded="false">
                    <span>How many modules does Dunco SMS include?</span>
                    <svg class="accordion-chevron" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="accordion-content">
                    <p>Dunco SMS includes 17 fully integrated modules: Academic, Examination, Finance, Attendance, Timetable, Library, Hostel, Transport, HR, Communication, Notifications, Portal, Documents, Settings, AI ChatBot, Core/Admin, and more.</p>
                </div>
            </div>
            <div class="accordion-item">
                <button class="accordion-trigger" aria-expanded="false">
                    <span>Is Dunco SMS mobile-friendly?</span>
                    <svg class="accordion-chevron" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="accordion-content">
                    <p>Yes. Dunco SMS is fully responsive and works on all devices including desktops, tablets, and smartphones.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section class="pricing-section" id="pricing">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header section-animate">
            <span class="section-tag">Pricing</span>
            <h2 class="section-title">Choose Your Plan</h2>
            <p class="section-subtitle">Flexible pricing plans to suit schools of all sizes.</p>
        </div>
        <div class="pricing-grid section-animate">
            @forelse($pricingPlans as $plan)
            <div class="pricing-card {{ ($plan->is_featured ?? false) ? 'pricing-card-featured' : '' }}">
                @if($plan->is_featured ?? false)
                <div class="pricing-badge">Most Popular</div>
                @endif
                <h3 class="pricing-plan-name">{{ $plan->name }}</h3>
                <div class="pricing-amount">
                    <span class="pricing-currency">{{ $plan->currency ?? '$' }}</span>
                    <span class="pricing-value">{{ number_format($plan->price, $plan->price == floor($plan->price) ? 0 : 2) }}</span>
                    <span class="pricing-period">/{{ $plan->billing_period ?? 'month' }}</span>
                </div>
                @if($plan->description ?? null)
                <p class="pricing-description">{{ $plan->description }}</p>
                @endif
                <ul class="pricing-features">
                    @foreach($plan->features as $feature)
                    <li>
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $feature->feature_text }}
                    </li>
                    @endforeach
                </ul>
                @guest
                <a href="{{ route('public.demo') }}" class="btn-primary-lg btn-full pricing-btn">
                    Get Started
                    <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="btn-primary-lg btn-full pricing-btn">
                    Go to Dashboard
                    <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @endauth
            </div>
            @empty
            <div class="pricing-card">
                <h3 class="pricing-plan-name">Starter</h3>
                <div class="pricing-amount">
                    <span class="pricing-currency">$</span>
                    <span class="pricing-value">49</span>
                    <span class="pricing-period">/month</span>
                </div>
                <p class="pricing-description">Perfect for small schools getting started with digital management.</p>
                <ul class="pricing-features">
                    <li><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Up to 200 Students</li>
                    <li><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Core Modules</li>
                    <li><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Email Support</li>
                </ul>
                <a href="{{ route('public.demo') }}" class="btn-primary-lg btn-full pricing-btn">Get Started</a>
            </div>
            <div class="pricing-card pricing-card-featured">
                <div class="pricing-badge">Most Popular</div>
                <h3 class="pricing-plan-name">Professional</h3>
                <div class="pricing-amount">
                    <span class="pricing-currency">$</span>
                    <span class="pricing-value">149</span>
                    <span class="pricing-period">/month</span>
                </div>
                <p class="pricing-description">Ideal for growing schools that need advanced features.</p>
                <ul class="pricing-features">
                    <li><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Up to 1,000 Students</li>
                    <li><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> All 17 Modules</li>
                    <li><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Priority Support</li>
                    <li><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> AI ChatBot</li>
                </ul>
                <a href="{{ route('public.demo') }}" class="btn-primary-lg btn-full pricing-btn">Get Started</a>
            </div>
            <div class="pricing-card">
                <h3 class="pricing-plan-name">Enterprise</h3>
                <div class="pricing-amount">
                    <span class="pricing-value">Custom</span>
                </div>
                <p class="pricing-description">For large institutions with custom requirements and dedicated support.</p>
                <ul class="pricing-features">
                    <li><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Unlimited Students</li>
                    <li><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> All Modules + Custom</li>
                    <li><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Dedicated Account Manager</li>
                    <li><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> On-Premise Option</li>
                </ul>
                <a href="{{ route('public.contact') }}" class="btn-primary-lg btn-full pricing-btn">Contact Sales</a>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section class="cta-section" id="cta">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="cta-box section-animate">
            <div class="cta-bg-pattern"></div>
            <h2>Ready to Transform Your School?</h2>
            <p>Join leading educational institutions already using Dunco SMS to streamline their operations. Schedule a free demo today.</p>
            <div class="cta-buttons">
                @guest
                    <a href="{{ route('public.demo') }}" class="btn-white-lg">
                        Request a Demo
                        <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('login') }}" class="btn-ghost-white-lg">Login to Dashboard</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn-white-lg">
                        Go to Dashboard
                        <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>

@endsection
