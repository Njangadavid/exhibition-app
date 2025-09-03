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
    
    // User Management routes (Admin only)
    Route::resource('users', \App\Http\Controllers\UserManagementController::class)->middleware('role:admin');
    Route::patch('users/{user}/toggle-status', [\App\Http\Controllers\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status')->middleware('role:admin');
    Route::get('users/roles', [\App\Http\Controllers\UserManagementController::class, 'roles'])->name('users.roles')->middleware('role:admin');
    Route::patch('users/roles/{role}/permissions', [\App\Http\Controllers\UserManagementController::class, 'updateRolePermissions'])->name('users.roles.permissions')->middleware('role:admin');
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
    
    // Reports routes
    Route::get('/events/{event}/reports/bookings', [EventController::class, 'bookingsReport'])->name('events.reports.bookings');
    Route::get('/events/{event}/reports/bookings/pdf', [EventController::class, 'bookingsReportPdf'])->name('events.reports.bookings.pdf');
    Route::get('/events/{event}/reports/booth-owner/{boothOwner}', [EventController::class, 'boothOwnerDetails'])->name('events.reports.booth-owner-details');
    
    // Booth Member routes (for admin access) - using web routes instead of API routes
    Route::put('/booth-members', [EventController::class, 'storeBoothMember'])->name('booth-members.store');
    Route::get('/booth-members/{member}/edit', [EventController::class, 'getBoothMemberForEdit'])->name('booth-members.edit');
    Route::get('/booth-members/new/{boothOwner}', [EventController::class, 'getFormFieldsForNewMember'])->name('booth-members.new');
    Route::put('/booth-members/{member}', [EventController::class, 'updateBoothMember'])->name('booth-members.update');
    Route::delete('/booth-members/{member}', [EventController::class, 'deleteBoothMember'])->name('booth-members.delete');
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

// Artisan Commands for cPanel (Web-based execution)
Route::prefix('artisan')->group(function () {
    // Clear caches
    Route::get('/clear-cache', function () {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully!',
                'commands' => ['cache:clear', 'config:clear', 'view:clear', 'route:clear']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing caches: ' . $e->getMessage()
            ], 500);
        }
    })->name('artisan.clear-cache');

    // Run migrations
    Route::get('/migrate', function () {
        try {
            \Artisan::call('migrate', ['--force' => true]);
            return response()->json([
                'success' => true,
                'message' => 'Migrations completed successfully!',
                'output' => \Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error running migrations: ' . $e->getMessage()
            ], 500);
        }
    })->name('artisan.migrate');

    // Run migrations with status
    Route::get('/migrate-status', function () {
        try {
            \Artisan::call('migrate:status');
            return response()->json([
                'success' => true,
                'message' => 'Migration status retrieved successfully!',
                'output' => \Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting migration status: ' . $e->getMessage()
            ], 500);
        }
    })->name('artisan.migrate-status');

    // Optimize application
    Route::get('/optimize', function () {
        try {
            \Artisan::call('optimize');
            \Artisan::call('config:cache');
            \Artisan::call('route:cache');
            \Artisan::call('view:cache');
            return response()->json([
                'success' => true,
                'message' => 'Application optimized successfully!',
                'commands' => ['optimize', 'config:cache', 'route:cache', 'view:cache']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error optimizing application: ' . $e->getMessage()
            ], 500);
        }
    })->name('artisan.optimize');

    // Clear and optimize
    Route::get('/clear-and-optimize', function () {
        try {
            // Clear everything first
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            
            // Then optimize
            \Artisan::call('optimize');
            \Artisan::call('config:cache');
            \Artisan::call('route:cache');
            \Artisan::call('view:cache');
            
            return response()->json([
                'success' => true,
                'message' => 'Application cleared and optimized successfully!',
                'commands' => [
                    'Cleared: cache, config, view, route',
                    'Optimized: application, config, route, view'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error clearing and optimizing: ' . $e->getMessage()
            ], 500);
        }
    })->name('artisan.clear-and-optimize');

    // Queue work (process pending jobs)
    Route::get('/queue-work', function () {
        try {
            \Artisan::call('queue:work', ['--once' => true]);
            return response()->json([
                'success' => true,
                'message' => 'Queue processed successfully!',
                'output' => \Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing queue: ' . $e->getMessage()
            ], 500);
        }
    })->name('artisan.queue-work');

    // Check application status
    Route::get('/status', function () {
        try {
            $status = [
                'app_name' => config('app.name'),
                'app_env' => config('app.env'),
                'app_debug' => config('app.debug'),
                'database_connection' => \DB::connection()->getPdo() ? 'Connected' : 'Failed',
                'cache_driver' => config('cache.default'),
                'queue_driver' => config('queue.default'),
                'storage_link' => file_exists(public_path('storage')) ? 'Linked' : 'Not Linked',
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'memory_usage' => memory_get_usage(true),
                'peak_memory' => memory_get_peak_usage(true)
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Application status retrieved successfully!',
                'status' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting status: ' . $e->getMessage()
            ], 500);
        }
    })->name('artisan.status');

    // Create storage link
    Route::get('/storage-link', function () {
        try {
            \Artisan::call('storage:link');
            return response()->json([
                'success' => true,
                'message' => 'Storage link created successfully!',
                'output' => \Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating storage link: ' . $e->getMessage()
            ], 500);
        }
    })->name('artisan.storage-link');

    // List all available commands
    Route::get('/list', function () {
        try {
            \Artisan::call('list');
            return response()->json([
                'success' => true,
                'message' => 'Available commands listed successfully!',
                'output' => \Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error listing commands: ' . $e->getMessage()
            ], 500);
        }
    })->name('artisan.list');

    // Build Vite assets for production
    Route::get('/build', function () {
        try {
            // Check if npm is available
            $npmPath = shell_exec('which npm 2>&1');
            if (empty($npmPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'NPM is not available on this server. Please build assets locally and upload them.',
                    'instructions' => [
                        '1. Run "npm run build" locally',
                        '2. Upload the "public/build" folder to your server',
                        '3. Ensure the build folder is in your public_html/public/ directory'
                    ]
                ], 500);
            }

            // Check if package.json exists
            if (!file_exists(base_path('package.json'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'package.json not found. Please ensure you have the correct project structure.'
                ], 500);
            }

            // Install dependencies if node_modules doesn't exist
            if (!is_dir(base_path('node_modules'))) {
                \Artisan::call('shell_exec', ['command' => 'npm install']);
            }

            // Build assets
            $buildCommand = 'npm run build';
            $output = shell_exec($buildCommand . ' 2>&1');
            
            if (file_exists(public_path('build/manifest.json'))) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vite assets built successfully!',
                    'output' => $output,
                    'manifest_exists' => true,
                    'build_path' => public_path('build')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Build completed but manifest.json not found. Check the output for errors.',
                    'output' => $output,
                    'manifest_exists' => false
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error building assets: ' . $e->getMessage(),
                'instructions' => [
                    '1. Run "npm run build" locally',
                    '2. Upload the "public/build" folder to your server',
                    '3. Ensure the build folder is in your public_html/public/ directory'
                ]
            ], 500);
        }
    })->name('artisan.build');

    // Check Vite build status
    Route::get('/build-status', function () {
        try {
            $manifestPath = public_path('build/manifest.json');
            $buildDir = public_path('build');
            
            $status = [
                'manifest_exists' => file_exists($manifestPath),
                'build_dir_exists' => is_dir($buildDir),
                'manifest_path' => $manifestPath,
                'build_dir_path' => $buildDir,
                'build_dir_contents' => is_dir($buildDir) ? scandir($buildDir) : [],
                'public_path' => public_path(),
                'base_path' => base_path()
            ];
            
            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
                $status['manifest_content'] = $manifest;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Build status checked successfully!',
                'status' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking build status: ' . $e->getMessage()
            ], 500);
        }
    })->name('artisan.build-status');
});

require __DIR__.'/auth.php';
