<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FormBuilderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
    
    // Form Builder routes
    Route::resource('events.form-builders', FormBuilderController::class);
    Route::get('/events/{event}/form-builders/{formBuilder}/design', [FormBuilderController::class, 'design'])->name('events.form-builders.design');
    Route::get('/events/{event}/form-builders/{formBuilder}/preview', [FormBuilderController::class, 'preview'])->name('events.form-builders.preview');
    Route::get('/events/{event}/form-builders/{formBuilder}/json', [FormBuilderController::class, 'getFormJson'])->name('events.form-builders.json');
});

require __DIR__.'/auth.php';
