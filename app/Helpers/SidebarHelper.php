<?php

namespace App\Helpers;

class SidebarHelper
{
    /**
     * Set the default sidebar collapsed state for the current page
     * NOTE: This is now optional - the sidebar will use global user preference by default
     * 
     * @param bool $collapsed
     * @return void
     */
    public static function setCollapsed(bool $collapsed = true): void
    {
        view()->share('sidebarCollapsed', $collapsed);
    }

    /**
     * Set sidebar to collapsed by default for this page only
     * NOTE: This overrides the global user preference
     * 
     * @return void
     */
    public static function collapse(): void
    {
        self::setCollapsed(true);
    }

    /**
     * Set sidebar to expanded by default for this page only
     * NOTE: This overrides the global user preference
     * 
     * @return void
     */
    public static function expand(): void
    {
        self::setCollapsed(false);
    }

    /**
     * Set global sidebar preference (for future use)
     * This would require a database field to store user preferences
     * 
     * @param bool $collapsed
     * @return void
     */
    public static function setGlobalPreference(bool $collapsed): void
    {
        // This could be implemented to store in user preferences
        // For now, we rely on localStorage in the frontend
    }
}
