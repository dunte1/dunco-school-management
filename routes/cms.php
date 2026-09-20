<?php

use Illuminate\Support\Facades\Route;

// Admin CMS Routes
Route::prefix('admin/cms')->name('admin.cms.')->middleware(['web', 'auth', 'admin'])->group(function () {
    // Testimonials
    Route::resource('testimonials', \App\Http\Controllers\Admin\Cms\TestimonialController::class);

    // FAQs
    Route::resource('faqs', \App\Http\Controllers\Admin\Cms\FaqController::class);

    // Pricing
    Route::resource('pricing', \App\Http\Controllers\Admin\Cms\PricingController::class);

    // Contact Enquiries
    Route::get('enquiries', [\App\Http\Controllers\Admin\Cms\ContactEnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('enquiries/{contactEnquiry}', [\App\Http\Controllers\Admin\Cms\ContactEnquiryController::class, 'show'])->name('enquiries.show');
    Route::patch('enquiries/{contactEnquiry}/status', [\App\Http\Controllers\Admin\Cms\ContactEnquiryController::class, 'updateStatus'])->name('enquiries.update-status');

    // Demo Requests
    Route::get('demos', [\App\Http\Controllers\Admin\Cms\DemoRequestController::class, 'index'])->name('demos.index');
    Route::get('demos/{demoRequest}', [\App\Http\Controllers\Admin\Cms\DemoRequestController::class, 'show'])->name('demos.show');
    Route::patch('demos/{demoRequest}/status', [\App\Http\Controllers\Admin\Cms\DemoRequestController::class, 'updateStatus'])->name('demos.update-status');

    // Leads
    Route::resource('leads', \App\Http\Controllers\Admin\Cms\LeadController::class)->only(['index', 'show']);
    Route::patch('leads/{lead}/status', [\App\Http\Controllers\Admin\Cms\LeadController::class, 'updateStatus'])->name('leads.update-status');

    // Public Settings
    Route::get('settings', [\App\Http\Controllers\Admin\Cms\PublicSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\Cms\PublicSettingController::class, 'update'])->name('settings.update');

    // Admissions
    Route::resource('admissions/cycles', \App\Http\Controllers\Admin\Cms\AdmissionCycleController::class)->except(['show']);
    Route::get('admissions/fields', [\App\Http\Controllers\Admin\Cms\AdmissionFieldController::class, 'index'])->name('admissions.fields.index');
    Route::get('admissions/fields/create', [\App\Http\Controllers\Admin\Cms\AdmissionFieldController::class, 'create'])->name('admissions.fields.create');
    Route::post('admissions/fields', [\App\Http\Controllers\Admin\Cms\AdmissionFieldController::class, 'store'])->name('admissions.fields.store');
    Route::get('admissions/fields/{admissionSection}/edit', [\App\Http\Controllers\Admin\Cms\AdmissionFieldController::class, 'edit'])->name('admissions.fields.edit');
    Route::put('admissions/fields/{admissionSection}', [\App\Http\Controllers\Admin\Cms\AdmissionFieldController::class, 'update'])->name('admissions.fields.update');
    Route::delete('admissions/fields/{admissionSection}', [\App\Http\Controllers\Admin\Cms\AdmissionFieldController::class, 'destroy'])->name('admissions.fields.destroy');
    Route::post('admissions/fields/{admissionSection}/fields', [\App\Http\Controllers\Admin\Cms\AdmissionFieldController::class, 'storeField'])->name('admissions.fields.store-field');
    Route::delete('admissions/fields/field/{admissionField}', [\App\Http\Controllers\Admin\Cms\AdmissionFieldController::class, 'destroyField'])->name('admissions.fields.destroy-field');
    Route::get('admissions/documents', [\App\Http\Controllers\Admin\Cms\AdmissionFieldController::class, 'documents'])->name('admissions.documents.index');
    Route::post('admissions/documents', [\App\Http\Controllers\Admin\Cms\AdmissionFieldController::class, 'storeDocument'])->name('admissions.documents.store');
    Route::delete('admissions/documents/{admissionDocumentType}', [\App\Http\Controllers\Admin\Cms\AdmissionFieldController::class, 'destroyDocument'])->name('admissions.documents.destroy');

    // Admission Applications
    Route::get('admissions/applications', [\App\Http\Controllers\Admin\Cms\AdmissionApplicationController::class, 'index'])->name('admissions.applications.index');
    Route::get('admissions/applications/{admissionApplication}', [\App\Http\Controllers\Admin\Cms\AdmissionApplicationController::class, 'show'])->name('admissions.applications.show');
    Route::patch('admissions/applications/{admissionApplication}/status', [\App\Http\Controllers\Admin\Cms\AdmissionApplicationController::class, 'updateStatus'])->name('admissions.applications.update-status');
    Route::post('admissions/applications/{admissionApplication}/notes', [\App\Http\Controllers\Admin\Cms\AdmissionApplicationController::class, 'addNote'])->name('admissions.applications.add-note');
    Route::post('admissions/applications/{admissionApplication}/enroll', [\App\Http\Controllers\Admin\Cms\AdmissionApplicationController::class, 'enroll'])->name('admissions.applications.enroll');
});
