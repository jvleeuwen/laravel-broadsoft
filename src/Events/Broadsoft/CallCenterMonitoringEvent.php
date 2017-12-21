<?php

namespace Jvleeuwen\Broadsoft\Events\Broadsoft;

use Jvleeuwen\Broadsoft\Events\DashboardEvent;

class CallCenterMonitoringEvent extends DashboardEvent
{
    /** @var array */
    public $CallCenterMonitoring;

    public function __construct(array $CallCenterMonitoring)
    {
        $this->CallCenterMonitoring = $CallCenterMonitoring;
    }

    public function broadcastAS()
    {
        return 'App\Events\Broadsoft\CallCenterMonitoringEvent';
    }
}
