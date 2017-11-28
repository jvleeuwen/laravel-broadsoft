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

class ErrorEvent extends DashboardEvent
{
    /** @var array */
    public $error;

    public function __construct(array $error)
    {
        $this->error = $error;
    }
}
