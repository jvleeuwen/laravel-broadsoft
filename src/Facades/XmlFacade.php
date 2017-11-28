<?php

namespace Jvleeuwen\Broadsoft\Facades;

use Illuminate\Support\Facades\Facade;

class XmlFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'xml';
    }
}
