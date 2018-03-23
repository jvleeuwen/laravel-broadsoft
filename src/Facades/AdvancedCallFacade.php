<?php

namespace Jvleeuwen\Broadsoft\Facades;

use Illuminate\Support\Facades\Facade;

class AdvancedCallFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'advancedcall';
    }
}
