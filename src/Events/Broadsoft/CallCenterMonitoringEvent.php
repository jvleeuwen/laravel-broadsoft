<?php

namespace Jvleeuwen\Broadsoft\Events\Broadsoft;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Jvleeuwen\Broadsoft\Events\DashboardEvent;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CallCenterMonitoringEvent extends DashboardEvent
{
    /** @var array */
    public $CallCenterMonitoring;

    public function __construct(array $CallCenterMonitoring)
    {
        $this->CallCenterMonitoring = $CallCenterMonitoring;
    }
}
