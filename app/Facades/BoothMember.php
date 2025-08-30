<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BoothMember extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'booth-member-helper';
    }
}
