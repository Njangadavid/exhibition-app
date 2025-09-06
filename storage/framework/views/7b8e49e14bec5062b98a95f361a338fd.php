<?php
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
?>

<div class="<?php echo e($colSize); ?> mb-3">
    <?php if($field->show_label !== false): ?>
        <label for="<?php echo e($fieldId); ?>" class="form-label">
            <?php echo e($field->label); ?>

            <?php if($isRequired): ?>
                <span class="text-danger">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    
    <?php switch($field->type):
        case ('text'): ?>
        <?php case ('email'): ?>
        <?php case ('phone'): ?>
        <?php case ('url'): ?>
        <?php case ('password'): ?>
            <input type="<?php echo e($field->type); ?>" 
                   class="<?php echo e($fieldClass); ?>" 
                   id="<?php echo e($fieldId); ?>" 
                   name="<?php echo e($fieldName); ?>"
                   placeholder="<?php echo e($field->placeholder); ?>"
                   value="<?php echo e($field->default_value); ?>"
                   <?php echo e($isRequired ? 'required' : ''); ?>

                   disabled>
            <?php break; ?>
            
        <?php case ('textarea'): ?>
            <textarea class="<?php echo e($fieldClass); ?>" 
                      id="<?php echo e($fieldId); ?>" 
                      name="<?php echo e($fieldName); ?>"
                      rows="3"
                      placeholder="<?php echo e($field->placeholder); ?>"
                      <?php echo e($isRequired ? 'required' : ''); ?>

                      disabled><?php echo e($field->default_value); ?></textarea>
            <?php break; ?>
            
        <?php case ('select'): ?>
            <select class="<?php echo e($fieldClass); ?>" 
                    id="<?php echo e($fieldId); ?>" 
                    name="<?php echo e($fieldName); ?>"
                    <?php echo e($isRequired ? 'required' : ''); ?>

                    disabled>
                <option value="">Select <?php echo e($field->label); ?></option>
                <?php if($field->options && is_array($field->options)): ?>
                    <?php $__currentLoopData = $field->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($option); ?>" <?php echo e($field->default_option == $option ? 'selected' : ''); ?>>
                            <?php echo e($option); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>
            <?php break; ?>
            
        <?php case ('checkbox'): ?>
            <div class="form-check">
                <input class="form-check-input" 
                       type="checkbox" 
                       id="<?php echo e($fieldId); ?>" 
                       name="<?php echo e($fieldName); ?>"
                       value="1"
                       <?php echo e($field->default_value ? 'checked' : ''); ?>

                       disabled>
                <label class="form-check-label" for="<?php echo e($fieldId); ?>">
                    <?php echo e($field->label); ?>

                    <?php if($isRequired): ?>
                        <span class="text-danger">*</span>
                    <?php endif; ?>
                </label>
            </div>
            <?php break; ?>
            
        <?php case ('radio'): ?>
            <?php if($field->options && is_array($field->options)): ?>
                <?php $__currentLoopData = $field->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="radio" 
                               id="<?php echo e($fieldId); ?>_<?php echo e($index); ?>" 
                               name="<?php echo e($fieldName); ?>"
                               value="<?php echo e($option); ?>"
                               <?php echo e($field->default_option == $option ? 'checked' : ''); ?>

                               disabled>
                        <label class="form-check-label" for="<?php echo e($fieldId); ?>_<?php echo e($index); ?>">
                            <?php echo e($option); ?>

                        </label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php break; ?>
            
        <?php case ('date'): ?>
            <input type="date" 
                   class="<?php echo e($fieldClass); ?>" 
                   id="<?php echo e($fieldId); ?>" 
                   name="<?php echo e($fieldName); ?>"
                   value="<?php echo e($field->default_value); ?>"
                   <?php echo e($isRequired ? 'required' : ''); ?>

                   disabled>
            <?php break; ?>
            
        <?php case ('number'): ?>
            <input type="number" 
                   class="<?php echo e($fieldClass); ?>" 
                   id="<?php echo e($fieldId); ?>" 
                   name="<?php echo e($fieldName); ?>"
                   placeholder="<?php echo e($field->placeholder); ?>"
                   value="<?php echo e($field->default_value); ?>"
                   <?php echo e($isRequired ? 'required' : ''); ?>

                   disabled>
            <?php break; ?>
            
        <?php case ('file'): ?>
            <input type="file" 
                   class="<?php echo e($fieldClass); ?>" 
                   id="<?php echo e($fieldId); ?>" 
                   name="<?php echo e($fieldName); ?>"
                   <?php echo e($isRequired ? 'required' : ''); ?>

                   disabled>
            <?php break; ?>
            
        <?php case ('hidden'): ?>
            <input type="hidden" 
                   id="<?php echo e($fieldId); ?>" 
                   name="<?php echo e($fieldName); ?>"
                   value="<?php echo e($field->default_value); ?>">
            <?php break; ?>
            
        <?php default: ?>
            <input type="text" 
                   class="<?php echo e($fieldClass); ?>" 
                   id="<?php echo e($fieldId); ?>" 
                   name="<?php echo e($fieldName); ?>"
                   placeholder="<?php echo e($field->placeholder); ?>"
                   value="<?php echo e($field->default_value); ?>"
                   <?php echo e($isRequired ? 'required' : ''); ?>

                   disabled>
    <?php endswitch; ?>
    
    <?php if($field->help_text): ?>
        <div class="form-text"><?php echo e($field->help_text); ?></div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\exhibition-app\resources\views/form-builders/partials/field-preview.blade.php ENDPATH**/ ?>