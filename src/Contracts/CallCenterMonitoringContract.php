<?php

namespace Jvleeuwen\Broadsoft\Contracts;

interface CallCenterMonitoringContract
{
    public function SaveToDB($CallCenterArray);
    public function GetCallCentersBySlug($slug);
}
