<?php

namespace Jvleeuwen\Broadsoft\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Jvleeuwen\Broadsoft\Services\XmlService;
use Jvleeuwen\Broadsoft\Events\Broadsoft\ErrorEvent;
use Jvleeuwen\Broadsoft\Services\CallCenterMonitoringService;
use Jvleeuwen\Broadsoft\Contracts\CallCenterMonitoringContract;
use Jvleeuwen\Broadsoft\Events\Broadsoft\CallCenterMonitoringEvent;

// use jvleeuwen\broadsoft\Controllers\EmailController;


class CallCenterMonitoringController extends Controller
{
    private $xml;

    public function __construct(XmlService $xml, CallCenterMonitoringService $broadsoft)
    {
        $this->xml = $xml;
        $this->broadsoft = $broadsoft;
    }

    /*
        Handle incomming XML messages for the Call Center Agent
    */
    public function Incomming(Request $request)
    {
        $xml = $this->xml->parse($request->getContent());
        $type = $this->xml->type($xml);
        $data = $this->broadsoft->$type($xml);
        return $this->broadsoft->SaveToDB($data);
    }
}
