<?php

namespace Jvleeuwen\Broadsoft\Services;

use Illuminate\Support\Facades\Log;
// use Jvleeuwen\Broadsoft\Events\Broadsoft\ErrorEvent;
use Jvleeuwen\Broadsoft\Events\Broadsoft\ErrorEvent;
use Jvleeuwen\Broadsoft\Contracts\CallCenterMonitoringContract;
use Jvleeuwen\Broadsoft\Events\Broadsoft\CallCenterMonitoringEvent;

class CallCenterMonitoringService
{
    private $CallCenterMonitoring;

    public function __construct(CallCenterMonitoringContract $CallCenterMonitoring)
    {
        $this->CallCenterMonitoring = $CallCenterMonitoring;
    }

    /*
        Get the event type from xml data
    */
    public function GetEventType($xml, $req)
    {
        $type = str_replace('xsi:', '', (string)$xml->eventData[0]->attributes('xsi1', true)->type);

        try {
            return $this->$type($xml); # Call the type function like AgentStateEvent for further XML handling
        } catch (\Error $e) {
            $data = array(
                'class' => __CLASS__,
                'method' => $type,
                'message'=> 'Invalid method, this incident will be reported',
                'data' => json_decode(json_encode($xml)),
                'trace' => (string)$e
            );
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return('class ' .$type . ' not found');
        }
    }

    /*
        Parse CallCenter Monitoring Events
    */
    public function CallCenterMonitoringEvent($xml)
    {
        try {
            $CallCenterMonitoringEvent = array(
                "eventType" => (string)"CallCenterMonitoringEvent",
                "eventID" => (string)$xml->eventID,
                "sequenceNumber" => (int)$xml->sequenceNumber,
                "subscriptionId" => (string)$xml->subscriptionId,
                "targetId" => (string)$xml->targetId,
                "averageHandlingTime" => (int)$xml->eventData->monitoringStatus->averageHandlingTime->value,
                "expectedWaitTime" => (int)$xml->eventData->monitoringStatus->expectedWaitTime->value,
                "averageSpeedOfAnswer" => (int)$xml->eventData->monitoringStatus->averageSpeedOfAnswer->value,
                "longestWaitTime" => (int)$xml->eventData->monitoringStatus->longestWaitTime->value,
                "numCallsInQueue" => (int)$xml->eventData->monitoringStatus->numCallsInQueue->value,
                "numAgentsAssigned" => (int)$xml->eventData->monitoringStatus->numAgentsAssigned,
                "numAgentsStaffed" => (int)$xml->eventData->monitoringStatus->numAgentsStaffed,
                "numStaffedAgentsIdle" => (int)$xml->eventData->monitoringStatus->numStaffedAgentsIdle,
                "numStaffedAgentsUnavailable" => (int)$xml->eventData->monitoringStatus->numStaffedAgentsUnavailable,
            );
            event(new CallCenterMonitoringEvent($CallCenterMonitoringEvent));
            return $CallCenterMonitoringEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallCenterMonitoringEvent');
            return null;
        }
    }

    /*
        Parse CallCenter Monitoring Events
    */
    public function SubscriptionTerminatedEvent($xml)
    {
        try {
            $SubscriptionTerminatedEvent = array(
                "eventType" => (string)"SubscriptionTerminatedEvent",
                "eventID" => (string)$xml->eventID,
                "sequenceNumber" => (int)$xml->sequenceNumber,
                "userId" => (string)$xml->userId,
                "subscriptionId" => (string)$xml->subscriptionId,
                "externalApplicationId" => (string)$xml->externalApplicationId,
                "httpContact" => (string)$xml->httpContact->uri,
            );
            event(new CallCenterMonitoringEvent($SubscriptionTerminatedEvent));
            return $SubscriptionTerminatedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' =>(string)$e]));
            return ('can not parse event: SubscriptionTerminatedEvent');
        }
    }

    public function SaveToDB($data)
    {
        $this->CallCenterMonitoring->SaveToDB($data);
    }
}
