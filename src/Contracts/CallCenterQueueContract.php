<?php

namespace Jvleeuwen\Broadsoft\Contracts;

interface CallCenterQueueContract
{
    public function SaveToDB($CallCenterArray);

    public function GetCallCentersBySlug($slug);
}
