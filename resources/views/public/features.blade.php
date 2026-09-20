@extends('layouts.public')

@section('title', 'Features - Dunco School Management System')
@section('meta_description', 'Explore all features of Dunco SMS. 17 integrated modules covering every aspect of school management.')

@section('content')

<section class="page-hero page-hero-dark">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="page-hero-content section-animate">
            <h1 class="page-hero-title">Powerful Features</h1>
            <p class="page-hero-subtitle">17 integrated modules designed to streamline every aspect of school management. From academics to finance, we have got you covered.</p>
        </div>
    </div>
</section>

<section class="features-detail-section">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="feature-detail-grid section-animate">

            <div class="feature-detail-card">
                <div class="feature-detail-icon fi-blue">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3>Academic</h3>
                <p>Manage students, classes, subjects, enrollment, grading, and academic records with powerful tools.</p>
                <ul class="feature-detail-list"><li>Student enrollment and records</li><li>Class and subject management</li><li>Grading and report cards</li><li>Academic year and term management</li></ul>
            </div>
            <div class="feature-detail-card">
                <div class="feature-detail-icon fi-purple">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3>Examination</h3>
                <p>Create, administer, and grade exams online with proctoring, analytics, and automated results.</p>
                <ul class="feature-detail-list"><li>Online and offline exam creation</li><li>Question bank management</li><li>Exam scheduling and proctoring</li><li>Result publishing and analytics</li></ul>
            </div>
            <div class="feature-detail-card">
                <div class="feature-detail-icon fi-green">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3>Finance</h3>
                <p>Complete financial management with automated billing, online payments, invoicing, and reporting.</p>
                <ul class="feature-detail-list"><li>Fee structure and billing</li><li>Online payment processing</li><li>Invoice and receipt generation</li><li>Financial reports and analytics</li></ul>
            </div>
            <div class="feature-detail-card">
                <div class="feature-detail-icon fi-orange">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3>Attendance</h3>
                <p>Track student and staff attendance with multiple verification methods including QR and biometric.</p>
                <ul class="feature-detail-list"><li>Daily attendance marking</li><li>QR code scanning</li><li>Biometric integration</li><li>Real-time attendance reports</li></ul>
            </div>
            <div class="feature-detail-card">
                <div class="feature-detail-icon fi-red">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3>Timetable</h3>
                <p>Automatically generate conflict-free timetables with smart scheduling algorithms.</p>
                <ul class="feature-detail-list"><li>Auto timetable generation</li><li>Conflict detection</li><li>Substitute teacher management</li><li>Schedule view per class/teacher</li></ul>
            </div>
            <div class="feature-detail-card">
                <div class="feature-detail-icon fi-teal">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3>Library</h3>
                <p>Manage books, members, borrowing, returns, and digital library resources.</p>
                <ul class="feature-detail-list"><li>Book catalog management</li><li>Borrowing and returns</li><li>Member management</li><li>Library analytics</li></ul>
            </div>
            <div class="feature-detail-card">
                <div class="feature-detail-icon fi-blue">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <h3>Hostel</h3>
                <p>Manage hostel rooms, allocations, wardens, and maintenance requests.</p>
                <ul class="feature-detail-list"><li>Room management</li><li>Student allocation</li><li>Warden assignments</li><li>Maintenance tracking</li></ul>
            </div>
            <div class="feature-detail-card">
                <div class="feature-detail-icon fi-purple">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 4h8m-6 4h4M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                </div>
                <h3>Transport</h3>
                <p>Manage vehicles, routes, drivers, trips, and real-time transport tracking.</p>
                <ul class="feature-detail-list"><li>Vehicle and route management</li><li>Driver assignments</li><li>Trip scheduling</li><li>Parent notifications</li></ul>
            </div>
            <div class="feature-detail-card">
                <div class="feature-detail-icon fi-green">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3>HR & Staff</h3>
                <p>Complete HR management with payroll, leave, contracts, and performance reviews.</p>
                <ul class="feature-detail-list"><li>Staff records management</li><li>Payroll processing</li><li>Leave management</li><li>Performance reviews</li></ul>
            </div>
            <div class="feature-detail-card">
                <div class="feature-detail-icon fi-orange">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3>Communication</h3>
                <p>Multi-channel communication with email, SMS, push notifications, and announcements.</p>
                <ul class="feature-detail-list"><li>Email and SMS messaging</li><li>Push notifications</li><li>Announcement system</li><li>Template management</li></ul>
            </div>
            <div class="feature-detail-card">
                <div class="feature-detail-icon fi-red">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <h3>AI ChatBot</h3>
                <p>Built-in AI assistant powered by Gemini and OpenAI for student queries and automation.</p>
                <ul class="feature-detail-list"><li>Student query handling</li><li>Administrative automation</li><li>Multi-language support</li><li>Context-aware responses</li></ul>
            </div>
        </div>
    </div>
</section>

<section class="cta-section" id="cta">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="cta-box section-animate">
            <div class="cta-bg-pattern"></div>
            <h2>Ready to Get Started?</h2>
            <p>Join leading schools already using Dunco SMS to streamline their operations.</p>
            <div class="cta-buttons">
                <a href="{ route('public.demo') }" class="btn-white-lg">
                    Request a Demo
                    <svg class="btn-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{ route('public.contact') }" class="btn-ghost-white-lg">Contact Sales</a>
            </div>
        </div>
    </div>
</section>

@endsection
