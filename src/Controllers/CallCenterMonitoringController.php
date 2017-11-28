<?php

namespace Jvleeuwen\Broadsoft\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Jvleeuwen\Broadsoft\Events\Broadsoft\ErrorEvent;
use Jvleeuwen\Broadsoft\Contracts\CallCenterMonitoringContract;
use Jvleeuwen\Broadsoft\Events\Broadsoft\CallCenterMonitoringEvent;

// use jvleeuwen\broadsoft\Controllers\EmailController;

class CallCenterMonitoringController extends Controller
{
    /*
        Handle incomming XML messages for the Call Center Agent
    */
    public function Incomming(Request $request)
    {
        try {
            $req = $request->getContent();
            $xml = simplexml_load_string($req, null, 0, 'xsi', true);
            return $this->GetEventType($xml, $req);
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent((string)$e));
            return null;
        }
    }

    // /*
    //     Get the event type from xml data
    // */
    // protected function GetEventType($xml, $req)
    // {
    //     $type = str_replace('xsi:', '', (string)$xml->eventData[0]->attributes('xsi1', true)->type);

    //     try {
    //         return $this->$type($xml); # Call the type function like AgentStateEvent for further XML handling
    //     } catch (\BadMethodCallException $e) {
    //         $data = array(
    //             'class' => __CLASS__,
    //             'method' => $type,
    //             'message'=> 'Invalid method, this incident will be reported',
    //             'data' => json_decode(json_encode($xml)),
    //             'trace' => (string)$e
    //         );
    //         // $this->email->sendDebug( __CLASS__, $type, json_encode($xml), (string)$e, $req);
    //         Log::error($e);
    //         event(new ErrorEvent((string)$e));
    //         return null;
    //     }
    // }

    // /*
    //     Parse CallCenter Monitoring Events
    // */
    // protected function CallCenterMonitoringEvent($xml)
    // {
    //     try {
    //         $CallCenterMonitoringEvent = array(
    //             "eventType" => (string)"CallCenterMonitoringEvent",
    //             "eventID" => (string)$xml->eventID,
    //             "sequenceNumber" => (int)$xml->sequenceNumber,
    //             "subscriptionId" => (string)$xml->subscriptionId,
    //             "targetId" => (string)$xml->targetId,
    //             "averageHandlingTime" => (int)$xml->eventData->monitoringStatus->averageHandlingTime->value,
    //             "expectedWaitTime" => (int)$xml->eventData->monitoringStatus->expectedWaitTime->value,
    //             "averageSpeedOfAnswer" => (int)$xml->eventData->monitoringStatus->averageSpeedOfAnswer->value,
    //             "longestWaitTime" => (int)$xml->eventData->monitoringStatus->longestWaitTime->value,
    //             "numCallsInQueue" => (int)$xml->eventData->monitoringStatus->numCallsInQueue->value,
    //             "numAgentsAssigned" => (int)$xml->eventData->monitoringStatus->numAgentsAssigned,
    //             "numAgentsStaffed" => (int)$xml->eventData->monitoringStatus->numAgentsStaffed,
    //             "numStaffedAgentsIdle" => (int)$xml->eventData->monitoringStatus->numStaffedAgentsIdle,
    //             "numStaffedAgentsUnavailable" => (int)$xml->eventData->monitoringStatus->numStaffedAgentsUnavailable,
    //         );
    //         $this->CallCenterMonitoring->SaveToDB($CallCenterMonitoringEvent);
    //         event(new \CallCenterMonitoringEvent($CallCenterMonitoringEvent));
    //         return null;
    //     } catch (\Exception $e) {
    //         Log::error($e);
    //         event(new ErrorEvent((string)$e));
    //         return $e;
    //         return null;
    //     }
    // }

    // /*
    //     Parse CallCenter Monitoring Events
    // */
    // protected function SubscriptionTerminatedEvent($xml)
    // {
    //     try {
    //         $SubscriptionTerminatedEvent = array(
    //             "eventType" => (string)"SubscriptionTerminatedEvent",
    //             "eventID" => (string)$xml->eventID,
    //             "sequenceNumber" => (int)$xml->sequenceNumber,
    //             "userId" => (string)$xml->userId,
    //             "subscriptionId" => (string)$xml->subscriptionId,
    //             "externalApplicationId" => (string)$xml->externalApplicationId,
    //             "httpContact" => (string)$xml->httpContact->uri,
    //             // "debug_dateAdded" => (string)Carbon::now(),
    //             // "debug_dateShowedOnScreen" => (string)""
    //         );
    //         event(new \CallCenterMonitoringEvent($SubscriptionTerminatedEvent));
    //         return null;
    //     } catch (\Exception $e) {
    //         Log::error($e);
    //         // event(new ErrorEvent((string)$e));
    //         return null;
    //     }
    // }
}
