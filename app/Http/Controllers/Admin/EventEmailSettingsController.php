<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventEmailSettings;
use App\Services\EmailConfigurationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
USE App\Helpers\SidebarHelper;
class EventEmailSettingsController extends Controller
{
    protected $emailConfigService;

    public function __construct(EmailConfigurationService $emailConfigService)
    {
        $this->emailConfigService = $emailConfigService;
    }

    /**
     * Show the email settings form for an event.
     */
    public function show(Event $event)
    {
        // Check if user has permission to manage email settings
        if (!auth()->user()->hasPermission('manage_email_settings')) {
            abort(403, 'You do not have permission to manage email settings.');
        }

        $emailSettings = $event->emailSettings;
        SidebarHelper::expand();
        return view('admin.events.email-settings', compact('event', 'emailSettings'));
    }

    /**
     * Store or update email settings for an event.
     */
    public function store(Request $request, Event $event)
    {
        // Check if user has permission to manage email settings
        if (!auth()->user()->hasPermission('manage_email_settings')) {
            abort(403, 'You do not have permission to manage email settings.');
        }

        $validationRules = [
            'smtp_host' => 'required|string|max:255',
            'smtp_port' => 'required|integer|min:1|max:65535',
            'smtp_username' => 'required|string|max:255',
            'smtp_encryption' => ['nullable', Rule::in(['tls', 'ssl', ''])],
            'send_as_email' => 'required|email|max:255',
            'send_as_name' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ];

        // Only require password if it's a new setting or if password is provided
        if (!$event->emailSettings || $request->filled('smtp_password')) {
            $validationRules['smtp_password'] = 'required|string|min:1';
        }

        $request->validate($validationRules);

        try {
            $settingsData = $request->only([
                'smtp_host',
                'smtp_port',
                'smtp_username',
                'smtp_encryption',
                'send_as_email',
                'send_as_name',
                'is_active'
            ]);

            // Only include password if it's provided
            if ($request->filled('smtp_password')) {
                $settingsData['smtp_password'] = $request->smtp_password;
            }

            $emailSettings = $this->emailConfigService->createOrUpdateSettings($event, $settingsData);

            return redirect()
                ->route('admin.events.email-settings', $event)
                ->with('success', 'Email settings saved successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to save email settings', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('admin.events.email-settings', $event)
                ->with('error', 'Failed to save email settings. Please try again.');
        }
    }

    /**
     * Test email configuration for an event.
     */
    public function test(Request $request, Event $event)
    {
        // Check if user has permission to manage email settings
        if (!auth()->user()->hasPermission('manage_email_settings')) {
            abort(403, 'You do not have permission to manage email settings.');
        }

        try {
            $request->validate([
                'test_email' => 'required|email'
            ]);

            Log::info('Starting email test', [
                'event_id' => $event->id,
                'test_email' => $request->test_email
            ]);

            $result = $this->emailConfigService->testConfiguration($event, $request->test_email);

            Log::info('Email test result', [
                'event_id' => $event->id,
                'result' => $result
            ]);

            // Reset email configuration after test
            $this->emailConfigService->resetToGlobal();

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Email test validation failed', [
                'event_id' => $event->id,
                'test_email' => $request->test_email,
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', array_flatten($e->errors()))
            ], 422);
        } catch (\Exception $e) {
            Log::error('Email test failed', [
                'event_id' => $event->id,
                'test_email' => $request->test_email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete email settings for an event.
     */
    public function destroy(Event $event)
    {
        // Check if user has permission to manage email settings
        if (!auth()->user()->hasPermission('manage_email_settings')) {
            abort(403, 'You do not have permission to manage email settings.');
        }

        try {
            $emailSettings = $event->emailSettings;
            
            if ($emailSettings) {
                $emailSettings->delete();
                
                return redirect()
                    ->route('events.index')
                    ->with('success', 'Email settings deleted successfully!');
            }

            return redirect()
                ->route('events.index')
                ->with('error', 'No email settings found for this event.');

        } catch (\Exception $e) {
            Log::error('Failed to delete email settings', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('events.index')
                ->with('error', 'Failed to delete email settings. Please try again.');
        }
    }
}