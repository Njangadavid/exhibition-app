/**
 * Form Renderer Utility
 * Handles dynamic form generation with sections and fields
 */
class FormRenderer {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error(`Container with ID '${containerId}' not found`);
        }
    }

    /**
     * Render a complete form with sections and fields
     * @param {Object} formData - Form configuration data
     * @param {Array} formData.fields - Array of field objects
     * @param {Array} formData.sections - Array of section objects
     * @param {Object} values - Current form values
     * @param {string} formId - Form ID attribute
     * @param {string} formClass - Form CSS class
     */
    renderForm(formData, values = {}, formId = 'dynamicForm', formClass = 'border rounded p-4 bg-light') {
        if (!this.container) return;

        console.log('=== FormRenderer.renderForm START ===');
        console.log('formData:', formData);
        console.log('values:', values);
        console.log('formId:', formId);
        console.log('formClass:', formClass);

        if (!formData || !formData.fields || formData.fields.length === 0) {
            console.log('No form data or fields found');
            this.container.innerHTML = `
                <div class="alert alert-info border-0 bg-light">
                    <i class="bi bi-info-circle me-2"></i>
                    No fields have been configured for this form yet.
                </div>
            `;
            return;
        }

        // Group fields by sections
        const { sections, standaloneFields } = this.groupFieldsBySections(formData);
        console.log('Grouped sections:', sections);
        console.log('Standalone fields:', standaloneFields);

        let formHtml = `<form id="${formId}" class="${formClass} p-3">`;

        // Render standalone fields first
        if (standaloneFields.length > 0) {
            formHtml += this.renderFieldRow(standaloneFields, values);
        }

        // Render sections
        Object.keys(sections).forEach(sectionId => {
            const section = sections[sectionId];
            const sectionName = this.getSectionName(sectionId, formData.sections);
            console.log('Rendering section:', sectionId, 'with name:', sectionName, 'sections data:', formData.sections);
            formHtml += this.renderSection(section, sectionName, values);
        });

        formHtml += '</form>';
        console.log('Final form HTML length:', formHtml.length);
        console.log('=== FormRenderer.renderForm END ===');
        this.container.innerHTML = formHtml;
    }

    /**
     * Group fields by sections
     */
    groupFieldsBySections(formData) {
        const sections = {};
        const standaloneFields = [];

        console.log('=== groupFieldsBySections START ===');
        console.log('Input formData.fields:', formData.fields);

        formData.fields.forEach(field => {
            console.log('Processing field:', field);
            
            // Skip section fields - they're not input fields
            if (field.type === 'section') {
                console.log('Skipping section field:', field);
                return;
            }
            
            if (field.section_id) {
                console.log('Field has section_id:', field.section_id);
                if (!sections[field.section_id]) {
                    sections[field.section_id] = [];
                }
                sections[field.section_id].push(field);
            } else {
                console.log('Field has no section_id, adding to standalone');
                standaloneFields.push(field);
            }
        });

        console.log('Final sections object:', sections);
        console.log('Final standaloneFields:', standaloneFields);
        console.log('=== groupFieldsBySections END ===');

        return { sections, standaloneFields };
    }

    /**
     * Get section name by ID
     */
    getSectionName(sectionId, sectionsData) {
        console.log('=== getSectionName START ===');
        console.log('Looking for section ID:', sectionId);
        console.log('sectionsData:', sectionsData);
        
        if (!sectionsData) {
            console.log('No sectionsData provided, returning default');
            return 'Section';
        }
        
        // Find the section by field_id (since sections are fields with type='section')
        const section = sectionsData.find(s => s.field_id == sectionId);
        
        console.log('Found section:', section);
        console.log('Section label:', section?.label);
        console.log('Section name:', section?.name);
        
        if (section) {
            const result = section.label || 'Section';
            console.log('Returning section name:', result);
            console.log('=== getSectionName END ===');
            return result;
        }
        
        // Fallback: if no section found, return 'Section'
        console.log('No section found, returning default');
        console.log('=== getSectionName END ===');
        return 'Section';
    }

    /**
     * Render a section with fields
     */
    renderSection(fields, sectionName, values) {
        let html = `
            <div class="form-section mb-3">
                <h6 class="text-dark fw-semibold mb-2 pb-1 border-bottom border-2 border-primary-subtle">
                    ${sectionName}
                </h6>
        `;

        // Group fields into rows based on width
        const rows = this.groupFieldsIntoRows(fields);
        
        rows.forEach(rowFields => {
            html += this.renderFieldRow(rowFields, values);
        });

        html += '</div>';
        return html;
    }

    /**
     * Group fields into rows based on their width
     */
    groupFieldsIntoRows(fields) {
        const rows = [];
        let currentRow = [];
        let currentRowWidth = 0;

        fields.forEach(field => {
            const fieldWidth = this.getFieldWidth(field.width);
            
            if (currentRowWidth + fieldWidth > 12) {
                // Start new row
                if (currentRow.length > 0) {
                    rows.push(currentRow);
                }
                currentRow = [field];
                currentRowWidth = fieldWidth;
            } else {
                // Add to current row
                currentRow.push(field);
                currentRowWidth += fieldWidth;
            }
        });

        // Add the last row
        if (currentRow.length > 0) {
            rows.push(currentRow);
        }

        return rows;
    }

    /**
     * Render a row of fields
     */
    renderFieldRow(fields, values) {
        let html = '<div class="row g-2">';
        
        fields.forEach(field => {
            html += this.renderField(field, values);
        });
        
        html += '</div>';
        return html;
    }

    /**
     * Render a single field
     */
    renderField(field, values) {
        const fieldWidth = this.getFieldWidth(field.width);
        const colClass = `col-md-${fieldWidth}`;
        const fieldValue = values[field.field_id] || '';
        
        const required = field.required ? 'required' : '';
        const requiredClass = field.required ? 'required-field' : '';
        const helpText = field.help_text ? `<div class="help-text small text-muted mt-1 opacity-75">${field.help_text}</div>` : '';
        const requiredIndicator = field.required ? '<span class="text-danger fw-bold">*</span>' : '';

        switch (field.type) {
            case 'text':
            case 'email':
            case 'tel':
            case 'phone':
            case 'number':
            case 'url':
            case 'password':
            case 'date':
                const inputType = field.type === 'phone' ? 'tel' : field.type;
                return `
                    <div class="${colClass} mb-2">
                        <label class="form-label fw-medium text-dark mb-1 ${requiredClass}">
                            ${field.label}
                            ${requiredIndicator}
                        </label>
                        <input type="${inputType}" class="form-control form-control-sm border border-light-subtle bg-white" name="form_responses[${field.field_id}]" 
                               value="${fieldValue}" ${required} placeholder="Enter ${field.label.toLowerCase()}">
                        ${helpText}
                    </div>
                `;

            case 'textarea':
                return `
                    <div class="${colClass} mb-2">
                        <label class="form-label fw-medium text-dark mb-1 ${requiredClass}">
                            ${field.label}
                            ${requiredIndicator}
                        </label>
                        <textarea class="form-control form-control-sm border border-light-subtle bg-white" name="form_responses[${field.field_id}]" 
                                  rows="2" ${required} placeholder="Enter ${field.label.toLowerCase()}">${fieldValue}</textarea>
                        ${helpText}
                    </div>
                `;

            case 'select':
                const options = this.parseFieldOptions(field.options);
                let optionsHtml = '<option value="">Select...</option>';
                options.forEach(option => {
                    const selected = option === fieldValue ? 'selected' : '';
                    optionsHtml += `<option value="${option}" ${selected}>${option}</option>`;
                });
                return `
                    <div class="${colClass} mb-2">
                        <label class="form-label fw-medium text-dark mb-1 ${requiredClass}">
                            ${field.label}
                            ${requiredIndicator}
                        </label>
                        <select class="form-select form-select-sm border border-light-subtle bg-white" name="form_responses[${field.field_id}]" ${required}>
                            ${optionsHtml}
                        </select>
                        ${helpText}
                    </div>
                `;

            case 'checkbox':
                const checkboxOptions = this.parseFieldOptions(field.options);
                if (checkboxOptions.length > 0) {
                    let checkboxHtml = '';
                    checkboxOptions.forEach(option => {
                        const checked = fieldValue && fieldValue.includes(option) ? 'checked' : '';
                        checkboxHtml += `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="form_responses[${field.field_id}][]" 
                                       value="${option}" ${checked} ${required}>
                                <label class="form-check-label">${option}</label>
                            </div>
                        `;
                    });
                    return `
                        <div class="${colClass} mb-2">
                            <label class="form-label fw-medium text-dark mb-1 ${requiredClass}">
                                ${field.label}
                                ${requiredIndicator}
                            </label>
                            <div class="d-flex flex-column gap-1">
                                ${checkboxHtml}
                            </div>
                            ${helpText}
                        </div>
                    `;
                }
                break;

            case 'radio':
                const radioOptions = this.parseFieldOptions(field.options);
                if (radioOptions.length > 0) {
                    let radioHtml = '';
                    radioOptions.forEach(option => {
                        const checked = option === fieldValue ? 'checked' : '';
                        radioHtml += `
                            <div class="form-check">
                                <input class="form-check-input" type="radio" 
                                       name="form_responses[${field.field_id}]" 
                                       value="${option}" ${checked} ${required}>
                                <label class="form-check-label">${option}</label>
                            </div>
                        `;
                    });
                    return `
                        <div class="${colClass} mb-2">
                            <label class="form-label fw-medium text-dark mb-1 ${requiredClass}">
                                ${field.label}
                                ${requiredIndicator}
                            </label>
                            <div class="d-flex flex-column gap-1">
                                ${radioHtml}
                            </div>
                            ${helpText}
                        </div>
                    `;
                }
                break;

            case 'file':
                return `
                    <div class="${colClass} mb-2">
                        <label class="form-label fw-medium text-dark mb-1 ${requiredClass}">
                            ${field.label}
                            ${requiredIndicator}
                        </label>
                        <input type="file" class="form-control form-control-sm border border-light-subtle bg-white" name="form_responses[${field.field_id}]" ${required}>
                        ${fieldValue ? `<div class="mt-1"><small class="text-muted opacity-75">Current file: ${fieldValue}</small></div>` : ''}
                        ${helpText}
                    </div>
                `;

            case 'hidden':
                return `<input type="hidden" name="form_responses[${field.field_id}]" value="${fieldValue}">`;

            default:
                return `
                    <div class="${colClass} mb-2">
                        <label class="form-label fw-medium text-dark mb-1 ${requiredClass}">
                            ${field.label}
                            ${requiredIndicator}
                        </label>
                        <input type="text" class="form-control form-control-sm border border-light-subtle bg-white" name="form_responses[${field.field_id}]" 
                               value="${fieldValue}" ${required} placeholder="Enter ${field.label.toLowerCase()}">
                        ${helpText}
                    </div>
                `;
        }
    }

    /**
     * Parse field options safely
     */
    parseFieldOptions(options) {
        if (!options) return [];
        
        try {
            if (typeof options === 'string') {
                return JSON.parse(options);
            } else if (Array.isArray(options)) {
                return options;
            }
        } catch (e) {
            console.warn('Failed to parse field options:', e);
        }
        
        return [];
    }

    /**
     * Convert field width to Bootstrap column width
     */
    getFieldWidth(width) {
        if (!width) return 6; // Default to half width
        
        switch (width.toLowerCase()) {
            case 'full':
                return 12;
            case 'half':
                return 6;
            case 'third':
            case 'one-third':
                return 4;
            case 'quarter':
            case 'one-quarter':
                return 3;
            case 'two-thirds':
                return 8;
            case 'three-quarters':
                return 9;
            default:
                // If it's a number, use it directly
                const numWidth = parseInt(width);
                if (!isNaN(numWidth)) {
                    return Math.min(numWidth, 12); // Ensure it doesn't exceed 12
                }
                return 6; // Default fallback
        }
    }

    /**
     * Clear the form container
     */
    clear() {
        if (this.container) {
            this.container.innerHTML = '';
        }
    }
}

// Make it available globally
window.FormRenderer = FormRenderer;
