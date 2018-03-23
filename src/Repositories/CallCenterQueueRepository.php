<?php

namespace Jvleeuwen\Broadsoft\Repositories;

use Jvleeuwen\Broadsoft\Contracts\CallCenterQueueContract;

class CallCenterQueueRepository implements CallCenterQueueContract
{
    public function SaveToDB($CallCenterArray)
    {
        return true;
    }

    public function GetCallCentersBySlug($slug)
    {
        return Callcenter::where('slug', $slug)->get();

    }
}
