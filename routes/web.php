<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FormBuilderController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaystackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public event routes (no authentication required)
Route::get('/event/{event}', [EventController::class, 'publicShow'])->name('events.public.show');
Route::get('/event/{event}/floorplan', [BookingController::class, 'publicFloorplan'])->name('events.public.floorplan');
Route::get('/event/{event}/floorplan/{accessToken}', [BookingController::class, 'publicFloorplanWithToken'])->name('events.public.floorplan-token');

// Public booking routes (no authentication required)
Route::get('/event/{eventSlug}/book/{itemId}', [BookingController::class, 'showOwnerForm'])->name('bookings.owner-form');
Route::get('/event/{eventSlug}/booking/{accessToken}/owner', [BookingController::class, 'showOwnerFormWithToken'])->name('bookings.owner-form-token');
Route::post('/event/{eventSlug}/book/{itemId}', [BookingController::class, 'processOwnerForm'])->name('bookings.process-owner');
Route::post('/event/{eventSlug}/booking/{accessToken}/owner', [BookingController::class, 'processOwnerFormWithToken'])->name('bookings.process-owner-token');
Route::post('/event/{eventSlug}/booking/{accessToken}/remove', [BookingController::class, 'removeBooking'])->name('bookings.remove');
Route::get('/event/{eventSlug}/booking/{accessToken}/change-space/{itemId}', [BookingController::class, 'changeSpace'])->name('bookings.change-space');
Route::get('/event/{eventSlug}/booking/{accessToken}/members', [BookingController::class, 'showMemberForm'])->name('bookings.member-form');
Route::post('/event/{eventSlug}/booking/{accessToken}/members', [BookingController::class, 'processMemberForm'])->name('bookings.process-members');
Route::post('/event/{eventSlug}/booking/{accessToken}/save-members', [BookingController::class, 'saveMembers'])->name('bookings.save-members');
Route::delete('/event/{eventSlug}/booking/{accessToken}/member/{memberId}', [BookingController::class, 'deleteMember'])->name('bookings.delete-member');
Route::post('/event/{eventSlug}/booking/{accessToken}/member/{memberId}/update', [BookingController::class, 'updateMember'])->name('bookings.update-member');
Route::get('/event/{eventSlug}/booking/{accessToken}/payment', [BookingController::class, 'showPayment'])->name('bookings.payment');
Route::post('/event/{eventSlug}/booking/{accessToken}/payment', [PaystackController::class, 'initializePayment'])->name('bookings.process-payment');
Route::get('/event/{eventSlug}/booking/{accessToken}/paystack/callback', [PaystackController::class, 'handleCallback'])->name('paystack.callback');
Route::get('/event/{eventSlug}/booking/{accessToken}/paystack/status', [PaystackController::class, 'showPaymentStatus'])->name('paystack.status');
Route::get('/event/{eventSlug}/booking/{accessToken}/success', [BookingController::class, 'showSuccess'])->name('bookings.success');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('payment-methods', \App\Http\Controllers\Admin\PaymentMethodController::class);
    Route::patch('payment-methods/{paymentMethod}/toggle-status', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'toggleStatus'])->name('payment-methods.toggle-status');
    Route::patch('payment-methods/{paymentMethod}/set-default', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'setDefault'])->name('payment-methods.set-default');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Event routes
    Route::resource('events', EventController::class);
    Route::get('/events-dashboard', [EventController::class, 'dashboard'])->name('events.dashboard');
    
    // Event-specific routes
    Route::get('/events/{event}/dashboard', [EventController::class, 'eventDashboard'])->name('events.dashboard');
    Route::get('/events/{event}/floorplan', [EventController::class, 'floorplan'])->name('events.floorplan');
    Route::post('/events/{event}/floorplan/save', [EventController::class, 'saveFloorplan'])->name('events.floorplan.save');
    Route::get('/events/{event}/floorplan/load', [EventController::class, 'loadFloorplan'])->name('events.floorplan.load');
    Route::get('/events/{event}/registration', [EventController::class, 'registration'])->name('events.registration');
    
    // Floorplan maintenance routes
    Route::get('/events/{event}/floorplan/check-orphaned-bookings', [EventController::class, 'checkOrphanedBookings'])->name('events.floorplan.check-orphaned');
    Route::post('/events/{event}/floorplan/cleanup-orphaned-bookings', [EventController::class, 'cleanupOrphanedBookings'])->name('events.floorplan.cleanup-orphaned');
    
    // Form Builder routes
    Route::resource('events.form-builders', FormBuilderController::class);
    Route::get('/events/{event}/form-builders/{formBuilder}/design', [FormBuilderController::class, 'design'])->name('events.form-builders.design');
    Route::get('/events/{event}/form-builders/{formBuilder}/preview', [FormBuilderController::class, 'preview'])->name('events.form-builders.preview');
    Route::get('/events/{event}/form-builders/{formBuilder}/json', [FormBuilderController::class, 'getFormJson'])->name('events.form-builders.json');
    
    // Email Template routes
    Route::resource('events.email-templates', \App\Http\Controllers\Admin\EmailTemplateController::class);
    Route::post('/events/{event}/email-templates/{emailTemplate}/clone', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'clone'])->name('events.email-templates.clone');
    Route::post('/events/{event}/email-templates/{emailTemplate}/test', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'test'])->name('events.email-templates.test');
    Route::patch('/events/{event}/email-templates/{emailTemplate}/toggle-status', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'toggleStatus'])->name('events.email-templates.toggle-status');
});

// Paystack routes
Route::post('/event/{eventSlug}/booking/{accessToken}/process-payment', [PaystackController::class, 'initializePayment'])->name('bookings.process-payment');
Route::get('/event/{eventSlug}/booking/{accessToken}/paystack-callback', [PaystackController::class, 'handleCallback'])->name('paystack.callback');
Route::get('/event/{eventSlug}/booking/{accessToken}/paystack-status', [PaystackController::class, 'showPaymentStatus'])->name('paystack.status');

// Receipt generation route
Route::get('/event/{eventSlug}/booking/{accessToken}/receipt', [PaystackController::class, 'generateReceipt'])->name('bookings.receipt');

// Resend payment email route
Route::post('/event/{eventSlug}/booking/{accessToken}/resend-payment-email', [BookingController::class, 'resendPaymentEmail'])->name('bookings.resend-payment-email');

// Resend member registration email route
Route::post('/event/{eventSlug}/booking/{accessToken}/resend-member-email', [BookingController::class, 'resendMemberEmail'])->name('bookings.resend-member-email');

require __DIR__.'/auth.php';
