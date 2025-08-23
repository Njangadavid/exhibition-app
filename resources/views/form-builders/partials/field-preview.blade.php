@php
    $fieldId = 'preview_' . $field->field_id;
    $fieldName = 'preview_' . $field->field_id;
    $isRequired = $field->required;
    $fieldClass = 'form-control';
    if ($isRequired) {
        $fieldClass .= ' required';
    }
    if ($field->css_class) {
        $fieldClass .= ' ' . $field->css_class;
    }
    
    // Determine column size based on width
    $colSize = match($field->width) {
        'quarter' => 'col-md-3',
        'third' => 'col-md-4', 
        'half' => 'col-md-6',
        'full' => 'col-12',
        default => 'col-12',
    };
@endphp

<div class="{{ $colSize }} mb-3">
    @if($field->show_label !== false)
        <label for="{{ $fieldId }}" class="form-label">
            {{ $field->label }}
            @if($isRequired)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    
    @switch($field->type)
        @case('text')
        @case('email')
        @case('phone')
        @case('url')
        @case('password')
            <input type="{{ $field->type }}" 
                   class="{{ $fieldClass }}" 
                   id="{{ $fieldId }}" 
                   name="{{ $fieldName }}"
                   placeholder="{{ $field->placeholder }}"
                   value="{{ $field->default_value }}"
                   {{ $isRequired ? 'required' : '' }}
                   disabled>
            @break
            
        @case('textarea')
            <textarea class="{{ $fieldClass }}" 
                      id="{{ $fieldId }}" 
                      name="{{ $fieldName }}"
                      rows="3"
                      placeholder="{{ $field->placeholder }}"
                      {{ $isRequired ? 'required' : '' }}
                      disabled>{{ $field->default_value }}</textarea>
            @break
            
        @case('select')
            <select class="{{ $fieldClass }}" 
                    id="{{ $fieldId }}" 
                    name="{{ $fieldName }}"
                    {{ $isRequired ? 'required' : '' }}
                    disabled>
                <option value="">Select {{ $field->label }}</option>
                @if($field->options && is_array($field->options))
                    @foreach($field->options as $option)
                        <option value="{{ $option }}" {{ $field->default_option == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                @endif
            </select>
            @break
            
        @case('checkbox')
            <div class="form-check">
                <input class="form-check-input" 
                       type="checkbox" 
                       id="{{ $fieldId }}" 
                       name="{{ $fieldName }}"
                       value="1"
                       {{ $field->default_value ? 'checked' : '' }}
                       disabled>
                <label class="form-check-label" for="{{ $fieldId }}">
                    {{ $field->label }}
                    @if($isRequired)
                        <span class="text-danger">*</span>
                    @endif
                </label>
            </div>
            @break
            
        @case('radio')
            @if($field->options && is_array($field->options))
                @foreach($field->options as $index => $option)
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="radio" 
                               id="{{ $fieldId }}_{{ $index }}" 
                               name="{{ $fieldName }}"
                               value="{{ $option }}"
                               {{ $field->default_option == $option ? 'checked' : '' }}
                               disabled>
                        <label class="form-check-label" for="{{ $fieldId }}_{{ $index }}">
                            {{ $option }}
                        </label>
                    </div>
                @endforeach
            @endif
            @break
            
        @case('date')
            <input type="date" 
                   class="{{ $fieldClass }}" 
                   id="{{ $fieldId }}" 
                   name="{{ $fieldName }}"
                   value="{{ $field->default_value }}"
                   {{ $isRequired ? 'required' : '' }}
                   disabled>
            @break
            
        @case('number')
            <input type="number" 
                   class="{{ $fieldClass }}" 
                   id="{{ $fieldId }}" 
                   name="{{ $fieldName }}"
                   placeholder="{{ $field->placeholder }}"
                   value="{{ $field->default_value }}"
                   {{ $isRequired ? 'required' : '' }}
                   disabled>
            @break
            
        @case('file')
            <input type="file" 
                   class="{{ $fieldClass }}" 
                   id="{{ $fieldId }}" 
                   name="{{ $fieldName }}"
                   {{ $isRequired ? 'required' : '' }}
                   disabled>
            @break
            
        @case('hidden')
            <input type="hidden" 
                   id="{{ $fieldId }}" 
                   name="{{ $fieldName }}"
                   value="{{ $field->default_value }}">
            @break
            
        @default
            <input type="text" 
                   class="{{ $fieldClass }}" 
                   id="{{ $fieldId }}" 
                   name="{{ $fieldName }}"
                   placeholder="{{ $field->placeholder }}"
                   value="{{ $field->default_value }}"
                   {{ $isRequired ? 'required' : '' }}
                   disabled>
    @endswitch
    
    @if($field->help_text)
        <div class="form-text">{{ $field->help_text }}</div>
    @endif
</div>
