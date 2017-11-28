<?php

namespace Jvleeuwen\Broadsoft\Facades;

use Illuminate\Support\Facades\Facade;

class CallCenterMonitoringFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'callcentermonitoring';
    }
}
