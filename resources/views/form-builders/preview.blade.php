<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $formBuilder->name }} - Form Preview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .preview-container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            overflow: hidden;
        }
        .preview-header {
            background: linear-gradient(135deg, {{ $formBuilder->theme_color }}, {{ $formBuilder->theme_color }}dd);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .preview-form { 
            padding: 2rem; 
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: {{ $formBuilder->theme_color }};
            box-shadow: 0 0 0 0.2rem {{ $formBuilder->theme_color }}33;
        }
        .btn-primary {
            background-color: {{ $formBuilder->theme_color }};
            border-color: {{ $formBuilder->theme_color }};
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: {{ $formBuilder->theme_color }}dd;
            border-color: {{ $formBuilder->theme_color }}dd;
            transform: translateY(-2px);
        }
        .field-group {
            margin-bottom: 1.5rem;
        }
        .required-indicator {
            color: #dc3545;
            font-weight: bold;
        }
        .help-text {
            color: #6c757d;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .preview-footer {
            background-color: #f8f9fa;
            padding: 1rem;
            text-align: center;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
        }
        .field-width-full { width: 100%; }
        .field-width-half { width: 48%; }
        .field-width-third { width: 31%; }
        .field-width-quarter { width: 23%; }
        @media (max-width: 768px) {
            .field-width-half, .field-width-third, .field-width-quarter { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="preview-container">
            <!-- Header -->
            <div class="preview-header">
                <h1 class="h2 mb-2">{{ $formBuilder->name }}</h1>
                @if($formBuilder->description)
                    <p class="mb-0 opacity-75">{{ $formBuilder->description }}</p>
                @endif
            </div>

            <!-- Form -->
            <div class="preview-form">
                <form id="previewForm">
                    @foreach($formBuilder->fields as $field)
                        <div class="field-group field-width-{{ $field->width }}">
                            @switch($field->type)
                                @case('text')
                                @case('email')
                                @case('phone')
                                @case('url')
                                @case('password')
                                    <label for="{{ $field->field_id }}" class="form-label">
                                        {{ $field->label }}
                                        @if($field->required)
                                            <span class="required-indicator">*</span>
                                        @endif
                                    </label>
                                    <input type="{{ $field->type }}" 
                                           class="form-control" 
                                           id="{{ $field->field_id }}" 
                                           name="{{ $field->field_id }}"
                                           placeholder="{{ $field->placeholder ?: 'Enter ' . strtolower($field->label) }}"
                                           @if($field->required) required @endif>
                                    @if($field->help_text)
                                        <div class="help-text">{{ $field->help_text }}</div>
                                    @endif
                                    @break

                                @case('textarea')
                                    <label for="{{ $field->field_id }}" class="form-label">
                                        {{ $field->label }}
                                        @if($field->required)
                                            <span class="required-indicator">*</span>
                                        @endif
                                    </label>
                                    <textarea class="form-control" 
                                              id="{{ $field->field_id }}" 
                                              name="{{ $field->field_id }}"
                                              rows="4"
                                              placeholder="{{ $field->placeholder ?: 'Enter ' . strtolower($field->label) }}"
                                              @if($field->required) required @endif></textarea>
                                    @if($field->help_text)
                                        <div class="help-text">{{ $field->help_text }}</div>
                                    @endif
                                    @break

                                @case('select')
                                    <label for="{{ $field->field_id }}" class="form-label">
                                        {{ $field->label }}
                                        @if($field->required)
                                            <span class="required-indicator">*</span>
                                        @endif
                                    </label>
                                    <select class="form-select" 
                                            id="{{ $field->field_id }}" 
                                            name="{{ $field->field_id }}"
                                            @if($field->required) required @endif>
                                        <option value="">Select {{ strtolower($field->label) }}</option>
                                        @if($field->options)
                                            @foreach($field->options as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if($field->help_text)
                                        <div class="help-text">{{ $field->help_text }}</div>
                                    @endif
                                    @break

                                @case('checkbox')
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="{{ $field->field_id }}" 
                                               name="{{ $field->field_id }}"
                                               @if($field->required) required @endif>
                                        <label class="form-check-label" for="{{ $field->field_id }}">
                                            {{ $field->label }}
                                            @if($field->required)
                                                <span class="required-indicator">*</span>
                                            @endif
                                        </label>
                                    </div>
                                    @if($field->help_text)
                                        <div class="help-text">{{ $field->help_text }}</div>
                                    @endif
                                    @break

                                @case('radio')
                                    <label class="form-label">
                                        {{ $field->label }}
                                        @if($field->required)
                                            <span class="required-indicator">*</span>
                                        @endif
                                    </label>
                                    @if($field->options)
                                        @foreach($field->options as $option)
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       id="{{ $field->field_id }}_{{ $loop->index }}" 
                                                       name="{{ $field->field_id }}" 
                                                       value="{{ $option }}"
                                                       @if($field->required) required @endif>
                                                <label class="form-check-label" for="{{ $field->field_id }}_{{ $loop->index }}">
                                                    {{ $option }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if($field->help_text)
                                        <div class="help-text">{{ $field->help_text }}</div>
                                    @endif
                                    @break

                                @case('file')
                                    <label for="{{ $field->field_id }}" class="form-label">
                                        {{ $field->label }}
                                        @if($field->required)
                                            <span class="required-indicator">*</span>
                                        @endif
                                    </label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="{{ $field->field_id }}" 
                                           name="{{ $field->field_id }}"
                                           @if($field->required) required @endif>
                                    @if($field->help_text)
                                        <div class="help-text">{{ $field->help_text }}</div>
                                    @endif
                                    @break

                                @case('date')
                                    <label for="{{ $field->field_id }}" class="form-label">
                                        {{ $field->label }}
                                        @if($field->required)
                                            <span class="required-indicator">*</span>
                                        @endif
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="{{ $field->field_id }}" 
                                           name="{{ $field->field_id }}"
                                           @if($field->required) required @endif>
                                    @if($field->help_text)
                                        <div class="help-text">{{ $field->help_text }}</div>
                                    @endif
                                    @break

                                @case('number')
                                    <label for="{{ $field->field_id }}" class="form-label">
                                        {{ $field->label }}
                                        @if($field->required)
                                            <span class="required-indicator">*</span>
                                        @endif
                                    </label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="{{ $field->field_id }}" 
                                           name="{{ $field->field_id }}"
                                           placeholder="{{ $field->placeholder ?: 'Enter ' . strtolower($field->label) }}"
                                           @if($field->required) required @endif>
                                    @if($field->help_text)
                                        <div class="help-text">{{ $field->help_text }}</div>
                                    @endif
                                    @break

                                @default
                                    <label for="{{ $field->field_id }}" class="form-label">
                                        {{ $field->label }}
                                        @if($field->required)
                                            <span class="required-indicator">*</span>
                                        @endif
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="{{ $field->field_id }}" 
                                           name="{{ $field->field_id }}"
                                           placeholder="{{ $field->placeholder ?: 'Enter ' . strtolower($field->label) }}"
                                           @if($field->required) required @endif>
                                    @if($field->help_text)
                                        <div class="help-text">{{ $field->help_text }}</div>
                                    @endif
                            @endswitch
                        </div>
                    @endforeach

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ $formBuilder->submit_button_text ?: 'Submit' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="preview-footer">
                <small>
                    <i class="bi bi-info-circle me-1"></i>
                    This is a preview of the form "{{ $formBuilder->name }}". 
                    Form submissions are not processed in preview mode.
                </small>
            </div>
        </div>

        <!-- Preview Info -->
        <div class="text-center mt-4">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary" onclick="window.close()">
                    <i class="bi bi-x-circle me-2"></i>Close Preview
                </button>
                <button type="button" class="btn btn-outline-info" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Print Form
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form submission handler (for preview purposes)
        document.getElementById('previewForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show preview message
            alert('This is a preview form. In the actual implementation, this would submit the form data.');
            
            // You could also show the form data that would be submitted
            const formData = new FormData(this);
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            console.log('Form data that would be submitted:', data);
        });

        // Add some interactivity for better preview experience
        document.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>
</body>
</html>
