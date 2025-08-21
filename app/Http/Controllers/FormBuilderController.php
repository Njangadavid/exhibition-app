<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormBuilder;
use App\Models\FormField;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FormBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $formBuilders = $event->formBuilders()->with('fields')->latest()->get();
        
        return view('form-builders.index', compact('event', 'formBuilders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        $fieldTypes = FormField::getFieldTypes();
        $widthOptions = FormField::getWidthOptions();
        
        return view('form-builders.create', compact('event', 'fieldTypes', 'widthOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'allow_multiple_submissions' => 'boolean',
            'require_login' => 'boolean',
            'send_confirmation_email' => 'boolean',
            'confirmation_message' => 'nullable|string',
            'redirect_url' => 'nullable|url',
            'submit_button_text' => 'string|max:255',
            'theme_color' => 'string|max:7',
        ]);

        try {
            DB::beginTransaction();

            // Create form builder
            $formBuilder = $event->formBuilders()->create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'allow_multiple_submissions' => $validated['allow_multiple_submissions'] ?? false,
                'require_login' => $validated['require_login'] ?? false,
                'send_confirmation_email' => $validated['send_confirmation_email'] ?? true,
                'confirmation_message' => $validated['confirmation_message'],
                'redirect_url' => $validated['redirect_url'],
                'submit_button_text' => $validated['submit_button_text'] ?? 'Submit',
                'theme_color' => $validated['theme_color'] ?? '#007bff',
            ]);

            DB::commit();

            return redirect()->route('events.form-builders.edit', [$event, $formBuilder])
                ->with('success', 'Form created successfully! Now design your form by adding fields.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error creating form builder: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, FormBuilder $formBuilder)
    {
        $formBuilder->load('fields');
        
        return view('form-builders.show', compact('event', 'formBuilder'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event, FormBuilder $formBuilder)
    {
        $formBuilder->load('fields');
        
        return view('form-builders.edit', compact('event', 'formBuilder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event, FormBuilder $formBuilder)
    {
        // Check if this is a form design submission (has fields data) or settings submission
        if ($request->has('fields')) {
            return $this->updateFormDesign($request, $event, $formBuilder);
        }

        // Handle form settings update
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'allow_multiple_submissions' => 'boolean',
            'require_login' => 'boolean',
            'send_confirmation_email' => 'boolean',
            'confirmation_message' => 'nullable|string',
            'redirect_url' => 'nullable|url',
            'submit_button_text' => 'string|max:255',
            'theme_color' => 'string|max:7',
        ]);

        try {
            DB::beginTransaction();

            // Update form builder
            $formBuilder->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'allow_multiple_submissions' => $validated['allow_multiple_submissions'] ?? false,
                'require_login' => $validated['require_login'] ?? false,
                'send_confirmation_email' => $validated['send_confirmation_email'] ?? true,
                'confirmation_message' => $validated['confirmation_message'],
                'redirect_url' => $validated['redirect_url'],
                'submit_button_text' => $validated['submit_button_text'] ?? 'Submit',
                'theme_color' => $validated['theme_color'] ?? '#007bff',
            ]);

            DB::commit();

            return redirect()->route('events.form-builders.edit', [$event, $formBuilder])
                ->with('success', 'Form settings updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error updating form builder: ' . $e->getMessage());
        }
    }

    /**
     * Update form design with fields
     */
    private function updateFormDesign(Request $request, Event $event, FormBuilder $formBuilder)
    {
        $validated = $request->validate([
            'fields' => 'required|array',
            'fields.*.original_id' => 'required|string',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.type' => 'required|string',
            'fields.*.required' => 'boolean',
            'fields.*.placeholder' => 'nullable|string',
            'fields.*.help_text' => 'nullable|string',
            'fields.*.width' => 'nullable|string',
            'fields.*.section_id' => 'nullable|string',
            'fields.*.options' => 'nullable|string',
            'fields.*.default_option' => 'nullable|string',
        ]);

                try {
            DB::beginTransaction();
            
            // Delete existing fields
            $formBuilder->fields()->delete();

            // First pass: Create a mapping of frontend IDs to database IDs
            $idMapping = [];
            $timestamp = time();
            $createdFields = [];
            
            // Create new fields with proper ID mapping
            foreach ($validated['fields'] as $index => $fieldData) {
                // Generate a new database field_id
                $newFieldId = 'field_' . $index . '_' . $timestamp;
                
                // Store the mapping from frontend ID to database ID
                $originalId = $fieldData['original_id'];
                $idMapping[$originalId] = $newFieldId;
                
                $field = [
                    'field_id' => $newFieldId,
                    'label' => $fieldData['label'],
                    'type' => $fieldData['type'],
                    'sort_order' => $index,
                    'required' => $fieldData['required'] ?? false,
                    'placeholder' => $fieldData['placeholder'] ?? null,
                    'help_text' => $fieldData['help_text'] ?? null,
                    'width' => $fieldData['width'] ?? 'full',
                    'section_id' => null, // We'll update this in the second pass
                ];

                // Handle options for select, checkbox, radio fields
                if (isset($fieldData['options']) && !empty($fieldData['options'])) {
                    $field['options'] = json_decode($fieldData['options'], true);
                }

                // Handle default option
                if (isset($fieldData['default_option']) && !empty($fieldData['default_option'])) {
                    $field['default_option'] = $fieldData['default_option'];
                }

                $createdField = $formBuilder->fields()->create($field);
                $createdFields[] = $createdField;
            }
            
            // Second pass: Update section_id references with correct mapped IDs
            foreach ($validated['fields'] as $index => $fieldData) {
                if (!empty($fieldData['section_id'])) {
                    $originalSectionId = $fieldData['section_id'];
                    
                    // Find the mapped section ID
                    if (isset($idMapping[$originalSectionId])) {
                        $mappedSectionId = $idMapping[$originalSectionId];
                        
                        // Update the field with the correct section_id
                        $createdFields[$index]->update(['section_id' => $mappedSectionId]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('events.form-builders.design', [$event, $formBuilder])
                ->with('success', 'Form design saved successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error saving form design: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, FormBuilder $formBuilder)
    {
        try {
            $formBuilder->delete();
            
            return redirect()->route('events.form-builders.index', $event)
                ->with('success', 'Form builder deleted successfully!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting form builder: ' . $e->getMessage());
        }
    }

    /**
     * Show the form design page
     */
    public function design(Event $event, FormBuilder $formBuilder)
    {
        $formBuilder->load('fields');
        $fieldTypes = FormField::getFieldTypes();
        $widthOptions = FormField::getWidthOptions();
        
        return view('form-builders.design', compact('event', 'formBuilder', 'fieldTypes', 'widthOptions'));
    }

    /**
     * Preview the form
     */
    public function preview(Event $event, FormBuilder $formBuilder)
    {
        $formBuilder->load('fields');
        
        return view('form-builders.preview', compact('event', 'formBuilder'));
    }

    /**
     * Get form data as JSON for frontend
     */
    public function getFormJson(Event $event, FormBuilder $formBuilder)
    {
        return response()->json($formBuilder->form_json);
    }
}
