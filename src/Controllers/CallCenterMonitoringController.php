<?php

namespace Jvleeuwen\Broadsoft\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Jvleeuwen\Broadsoft\Events\Broadsoft\ErrorEvent;
use Jvleeuwen\Broadsoft\Contracts\CallCenterMonitoringContract;
use Jvleeuwen\Broadsoft\Events\Broadsoft\CallCenterMonitoringEvent;
use xml;
// use jvleeuwen\broadsoft\Controllers\EmailController;


class CallCenterMonitoringController extends Controller
{
    // private $xml;

    // public function __construct(XmlFacade $xml)
    // {
    //     $this->xml = $xml;
    // }

    /*
        Handle incomming XML messages for the Call Center Agent
    */
    public function Incomming(Request $request)
    {
        $xml = xml::parse($request);
        $type = xml::type($xml);
        // return the data here restructured or to null.
        // make the request to save to the database asa well
        // $val = $this->xml::parser($request);
        // dd($val);
        // detecteer welke class er aangeroepen moet worden
    }
}
