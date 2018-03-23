<?php

namespace Jvleeuwen\Broadsoft\Events\Broadsoft;

use Jvleeuwen\Broadsoft\Events\DashboardEvent;

class AdvancedCallEvent extends DashboardEvent
{
    /** @var array */
    public $CallCenterMonitoring;

    public function __construct(array $AdvancedCall)
    {
        $this->AdvancedCall = $AdvancedCall;
    }

    public function broadcastAS()
    {
        return 'App\Events\Broadsoft\AdvancedCallEvent';
    }
}
