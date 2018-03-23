<?php

namespace Jvleeuwen\Broadsoft\Facades;

use Illuminate\Support\Facades\Facade;

class CallCenterQueueFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'callcenterqueue';
    }
}
