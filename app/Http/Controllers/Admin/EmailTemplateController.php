<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Services\EmailCommunicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class EmailTemplateController extends Controller
{
    protected $emailService;

    public function __construct(EmailCommunicationService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of email templates for an event
     */
    public function index(Event $event)
    {
        $templates = EmailTemplate::where('event_id', $event->id)
            ->orderBy('trigger_type')
            ->orderBy('name')
            ->get();

        return view('admin.email-templates.index', compact('event', 'templates'));
    }

    /**
     * Show the form for creating a new email template
     */
    public function create(Event $event)
    {
        $triggerTypes = EmailTemplate::getTriggerTypes();
        $mergeFields = (new EmailTemplate())->getAvailableMergeFields();

        return view('admin.email-templates.create', compact('event', 'triggerTypes', 'mergeFields'));
    }

    /**
     * Store a newly created email template
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'trigger_type' => ['required', Rule::in(array_keys(EmailTemplate::getTriggerTypes()))],
            'conditions' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $validated['event_id'] = $event->id;
        $validated['is_active'] = $request->has('is_active');

        try {
            EmailTemplate::create($validated);

            return redirect()
                ->route('admin.email-templates.index', $event)
                ->with('success', 'Email template created successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to create email template', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create email template. Please try again.');
        }
    }

    /**
     * Display the specified email template
     */
    public function show(Event $event, EmailTemplate $emailTemplate)
    {
        $mergeFields = $emailTemplate->getAvailableMergeFields();
        $sampleData = $this->getSampleData($emailTemplate);

        return view('admin.email-templates.show', compact('event', 'emailTemplate', 'mergeFields', 'sampleData'));
    }

    /**
     * Show the form for editing the specified email template
     */
    public function edit(Event $event, EmailTemplate $emailTemplate)
    {
        $triggerTypes = EmailTemplate::getTriggerTypes();
        $mergeFields = $emailTemplate->getAvailableMergeFields();

        return view('admin.email-templates.edit', compact('event', 'emailTemplate', 'triggerTypes', 'mergeFields'));
    }

    /**
     * Update the specified email template
     */
    public function update(Request $request, Event $event, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'trigger_type' => ['required', Rule::in(array_keys(EmailTemplate::getTriggerTypes()))],
            'conditions' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            $emailTemplate->update($validated);

            return redirect()
                ->route('admin.email-templates.index', $event)
                ->with('success', 'Email template updated successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to update email template', [
                'template_id' => $emailTemplate->id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update email template. Please try again.');
        }
    }

    /**
     * Remove the specified email template
     */
    public function destroy(Event $event, EmailTemplate $emailTemplate)
    {
        try {
            $emailTemplate->delete();

            return redirect()
                ->route('admin.email-templates.index', $event)
                ->with('success', 'Email template deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to delete email template', [
                'template_id' => $emailTemplate->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to delete email template. Please try again.');
        }
    }

    /**
     * Clone an existing email template
     */
    public function clone(Event $event, EmailTemplate $emailTemplate)
    {
        try {
            $clonedTemplate = $emailTemplate->clone();

            return redirect()
                ->route('admin.email-templates.edit', [$event, $clonedTemplate])
                ->with('success', 'Email template cloned successfully! You can now edit the copy.');

        } catch (\Exception $e) {
            Log::error('Failed to clone email template', [
                'template_id' => $emailTemplate->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to clone email template. Please try again.');
        }
    }

    /**
     * Test email template with sample data
     */
    public function test(Event $event, EmailTemplate $emailTemplate)
    {
        try {
            $sampleData = $this->getSampleData($emailTemplate);
            $result = $this->emailService->testTemplate($emailTemplate, $sampleData);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Failed to test email template', [
                'template_id' => $emailTemplate->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to test template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle template active status
     */
    public function toggleStatus(Event $event, EmailTemplate $emailTemplate)
    {
        try {
            $emailTemplate->update(['is_active' => !$emailTemplate->is_active]);

            $status = $emailTemplate->is_active ? 'activated' : 'deactivated';

            return redirect()
                ->route('admin.email-templates.index', $event)
                ->with('success', "Email template {$status} successfully!");

        } catch (\Exception $e) {
            Log::error('Failed to toggle email template status', [
                'template_id' => $emailTemplate->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to update template status. Please try again.');
        }
    }

    /**
     * Get sample data for template testing
     */
    private function getSampleData(EmailTemplate $template): array
    {
        return [
            'event' => [
                'name' => 'Sample Exhibition Event',
                'start_date' => 'December 15, 2024',
                'end_date' => 'December 17, 2024',
                'venue' => 'Sample Convention Center'
            ],
            'owner' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'company' => 'Sample Company Ltd',
                'phone' => '+1 (555) 123-4567'
            ],
            'booth' => [
                'number' => 'A-15',
                'type' => 'Premium Booth',
                'price' => '2,500.00',
                'location' => 'Main Hall'
            ],
            'member' => [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'role' => 'Sales Representative'
            ],
            'payment' => [
                'amount' => '2,500.00',
                'method' => 'Credit Card',
                'date' => 'December 10, 2024',
                'reference' => 'PAY-12345'
            ]
        ];
    }
}
