<?php

namespace Jvleeuwen\Broadsoft\Contracts;

interface AdvancedCallContract
{
    public function SaveToDB($CallCenterArray);
    public function GetCallCentersBySlug($slug);
}
