<?php

namespace Jvleeuwen\Broadsoft\Facades;

use Illuminate\Support\Facades\Facade;

class CallCenterAgentFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'callcenteragent';
    }
}
