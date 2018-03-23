<?php

namespace Jvleeuwen\Broadsoft\Events\Broadsoft;

use Jvleeuwen\Broadsoft\Events\DashboardEvent;

class CallCenterAgentEvent extends DashboardEvent
{
    /** @var array */
    public $CallCenterMonitoring;

    public function __construct(array $CallCenterAgent)
    {
        $this->CallCenterAgent = $CallCenterAgent;
    }

    public function broadcastAS()
    {
        return 'App\Events\Broadsoft\CallCenterAgentEvent';
    }
}
