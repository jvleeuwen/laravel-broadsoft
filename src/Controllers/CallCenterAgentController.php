<?php

namespace Jvleeuwen\Broadsoft\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jvleeuwen\Broadsoft\Services\XmlService;
use Jvleeuwen\Broadsoft\Services\CallCenterAgentService;

class CallCenterAgentController extends Controller
{
    private $xml;

    public function __construct(XmlService $xml, CallCenterAgentService $broadsoft)
    {
        $this->xml = $xml;
        $this->broadsoft = $broadsoft;
    }

    /*
        Handle incomming XML messages for the Call Center Monitoring
    */
    public function Incomming(Request $request)
    {
        $xml = $this->xml->parse($request->getContent());
        $type = $this->xml->type($xml);
        $data = $this->broadsoft->$type($xml);
        // return $data;
        return $this->broadsoft->SaveToDB($data);
    }
}
