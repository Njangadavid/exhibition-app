# Sidebar Helper Usage

The `SidebarHelper` class provides an easy way to control the default collapsed state of the event layout sidebar.

## Usage

### In Controllers

```php
use App\Helpers\SidebarHelper;

class YourController extends Controller
{
    public function yourMethod(Event $event)
    {
        // Collapse sidebar by default
        SidebarHelper::collapse();
        
        // Or expand sidebar by default
        SidebarHelper::expand();
        
        // Or set custom state
        SidebarHelper::setCollapsed(true);  // collapsed
        SidebarHelper::setCollapsed(false); // expanded
        
        return view('your.view', compact('event'));
    }
}
```

### Examples

**Floorplan View (Collapsed by default):**
```php
public function floorplan(Event $event)
{
    SidebarHelper::collapse();
    return view('events.floorplan', compact('event'));
}
```

**Dashboard View (Expanded by default):**
```php
public function eventDashboard(Event $event)
{
    SidebarHelper::expand();
    return view('events.dashboard', compact('event'));
}
```

## Features

- ✅ **Page-specific defaults**: Each page can set its own default sidebar state
- ✅ **User preference**: Remembers user's toggle state in localStorage
- ✅ **Priority system**: Page defaults override user preferences
- ✅ **Smooth animations**: CSS transitions for collapsing/expanding
- ✅ **Mobile responsive**: Different behavior on mobile devices
- ✅ **Icon updates**: Toggle button icon changes based on state

## Behavior

1. **Page Load**: If a page sets a default state, it takes priority
2. **User Toggle**: User can toggle sidebar, state is saved to localStorage
3. **Subsequent Visits**: If no page default is set, uses saved user preference
4. **Mobile**: Sidebar becomes an overlay that slides in/out

## CSS Classes

- `.sidebar`: Base sidebar styles
- `.sidebar.collapsed`: Collapsed state (60px width, icons only)
- `.sidebar.show`: Mobile overlay state
