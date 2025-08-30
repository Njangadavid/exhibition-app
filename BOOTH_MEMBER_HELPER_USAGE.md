# BoothMemberHelper Usage Guide

## Overview
The `BoothMemberHelper` class provides a clean and consistent way to extract field values from booth member data based on `field_purpose`. It handles the complexity of navigating the JSON structure of `form_responses` and `form_fields`.

## Basic Usage

### 1. Get a Single Field Value
```php
use App\Helpers\BoothMemberHelper;

// Get member name - pass the actual BoothMember model
$memberName = BoothMemberHelper::getFieldValueByPurpose(
    $boothMember, 
    'member_name', 
    'Unknown Member'
);

// Get member email
$memberEmail = BoothMemberHelper::getFieldValueByPurpose(
    $boothMember, 
    'member_email', 
    'No email'
);
```

### 2. Get Multiple Field Values at Once
```php
// Get multiple fields in one call
$fieldValues = BoothMemberHelper::getMultipleFieldValues(
    $memberData,
    ['member_name', 'member_email', 'member_phone', 'member_company'],
    [
        'member_name' => 'Unknown Member',
        'member_email' => 'No email'
    ]
);

// Result:
// [
//     'member_name' => 'John Doe',
//     'member_email' => 'john@example.com',
//     'member_phone' => '+1234567890',
//     'member_company' => 'ACME Corp'
// ]
```

### 3. Get Field Label by Purpose
```php
$fieldLabel = BoothMemberHelper::getFieldLabelByPurpose(
    $memberData,
    'member_name',
    'Name' // default label
);
```

### 4. Check if Field Purpose Exists
```php
if (BoothMemberHelper::hasFieldPurpose($memberData, 'member_phone')) {
    // Field exists, safe to use
    $phone = BoothMemberHelper::getFieldValueByPurpose($memberData, 'member_phone');
}
```

### 5. Get All Available Field Purposes
```php
$availablePurposes = BoothMemberHelper::getAvailableFieldPurposes($memberData);
// Returns: ['member_name', 'member_email', 'member_phone', 'member_company']
```

### 6. Get Complete Member Summary
```php
$summary = BoothMemberHelper::getMemberSummary($memberData);
// Returns comprehensive member data including all common fields
```

## Data Structure Expected

The helper expects a `BoothMember` model with these relationships:
- **BoothMember** → `form_responses` (JSON field with field_id keys)
- **BoothMember** → **BoothOwner** → **Booking** → **Event** → **FormBuilders**
- **FormBuilder** (type: 'member_registration') → **FormFields**
- **FormField** (field_purpose: 'member_name') → `field_id`
- **FormField.field_id** → **form_responses[field_id]** → **Value**

The helper automatically navigates through these relationships to find the correct field value.

## Blade Template Usage

### In Views
```php
@php
    $memberName = \App\Helpers\BoothMemberHelper::getFieldValueByPurpose(
        ['form_responses' => $member->form_responses, 'form_fields' => $member->form_fields], 
        'member_name', 
        'Unknown Member'
    );
@endphp

<h3>{{ $memberName }}</h3>
```

### Using Facade (if registered)
```php
@php
    $memberName = \App\Facades\BoothMember::getFieldValueByPurpose(
        ['form_responses' => $member->form_responses, 'form_fields' => $member->form_fields], 
        'member_name', 
        'Unknown Member'
    );
@endphp
```

## Controller Usage

### In Controllers
```php
use App\Helpers\BoothMemberHelper;

class BoothMemberController extends Controller
{
    public function show(BoothMember $member)
    {
        $memberData = [
            'form_responses' => $member->form_responses,
            'form_fields' => $member->form_fields
        ];
        
        $memberInfo = BoothMemberHelper::getMultipleFieldValues(
            $memberData,
            ['member_name', 'member_email', 'member_phone']
        );
        
        return view('booth-members.show', compact('memberInfo'));
    }
}
```

## Fallback Pattern Matching

When `field_purpose` is not available, the helper uses intelligent pattern matching:

- **member_name**: Looks for text fields without @ symbols, reasonable length
- **member_email**: Looks for valid email addresses
- **member_phone**: Looks for phone number patterns
- **member_company**: Looks for company name patterns
- **member_title**: Looks for job title patterns

## Error Handling

The helper gracefully handles missing or malformed data:
```php
// If data is missing, returns default value
$name = BoothMemberHelper::getFieldValueByPurpose(
    [], // empty data
    'member_name', 
    'Unknown Member'
); // Returns: 'Unknown Member'

// If field_purpose doesn't exist, tries pattern matching
$email = BoothMemberHelper::getFieldValueByPurpose(
    $memberData,
    'member_email',
    'No email'
); // Will try to find email-like values
```

## Performance Considerations

- The helper iterates through fields to find matches, so for very large datasets, consider caching results
- Use `getMultipleFieldValues()` when you need multiple fields to avoid multiple iterations
- The helper is designed for typical booth member data sizes (usually < 20 fields)

## Common Use Cases

1. **Display Member Information**: Extract name, email, phone for display
2. **Form Pre-filling**: Get existing values when editing members
3. **Data Export**: Extract specific fields for CSV/Excel export
4. **Search/Filter**: Find members by specific field values
5. **Validation**: Check if required fields have values

## Example: Complete Member Display

```php
@foreach($boothMembers as $member)
    @php
        $memberData = [
            'form_responses' => $member->form_responses,
            'form_fields' => $member->form_fields
        ];
        
        $memberInfo = \App\Helpers\BoothMemberHelper::getMultipleFieldValues(
            $memberData,
            ['member_name', 'member_email', 'member_phone', 'member_company']
        );
    @endphp
    
    <div class="member-card">
        <h4>{{ $memberInfo['member_name'] }}</h4>
        <p>Email: {{ $memberInfo['member_email'] }}</p>
        @if($memberInfo['member_phone'])
            <p>Phone: {{ $memberInfo['member_phone'] }}</p>
        @endif
        @if($memberInfo['member_company'])
            <p>Company: {{ $memberInfo['member_company'] }}</p>
        @endif
    </div>
@endforeach
```

This helper class makes it much easier to work with the complex JSON structure of booth member data while providing consistent fallbacks and intelligent pattern matching.
