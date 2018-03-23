<?php

namespace Jvleeuwen\Broadsoft\Events\Broadsoft;

use Jvleeuwen\Broadsoft\Events\DashboardEvent;

class CallCenterQueueEvent extends DashboardEvent
{
    /** @var array */
    public $CallCenterQueue;

    public function __construct(array $CallCenterQueue)
    {
        $this->CallCenterQueue = $CallCenterQueue;
    }

    public function broadcastAS()
    {
        return 'App\Events\Broadsoft\CallCenterQueueEvent';
    }
}
