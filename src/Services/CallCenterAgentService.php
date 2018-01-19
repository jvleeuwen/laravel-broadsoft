<?php

namespace Jvleeuwen\Broadsoft\Services;

use Illuminate\Support\Facades\Log;
use Jvleeuwen\Broadsoft\Events\Broadsoft\ErrorEvent;
use Jvleeuwen\Broadsoft\Contracts\CallCenterAgentContract;
use Jvleeuwen\Broadsoft\Events\Broadsoft\CallCenterAgentEvent;

class CallCenterAgentService
{
    private $CallCenterAgent;

    public function __construct(CallCenterAgentContract $CallCenterAgent)
    {
        $this->CallCenterAgent = $CallCenterAgent;
    }

    public function GetEventType($xml, $req)
    {
        $type = str_replace('xsi:', '', (string)$xml->eventData[0]->attributes('xsi1', true)->type);

        try {
            return $this->$type($xml); // Call the type function like AgentStateEvent for further XML handling
        } catch (\Error $e) {
            $data = [
                'class' => __CLASS__,
                'method' => $type,
                'message' => 'Invalid method, this incident will be reported',
                'data' => json_decode(json_encode($xml)),
                'trace' => (string)$e
            ];
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return('class ' . $type . ' not found');
        }
    }

    /*
        Parse CallCenter Monitoring Events
    */
    public function ACDAgentJoinUpdateEvent($xml)
    {
        try {
            $ACDAgentJoinUpdateEvent = [
                'eventType' => (string)'ACDAgentJoinUpdateEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'subscriptionId' => (string)$xml->subscriptionId,
                'targetId' => (string)$xml->targetId,
                'userId' => (string)$xml->userId,
                'acdUserId' => (string)$xml->eventData->ccAgentJoinUpdateData->joinInfo->acdUserId,
                'skillLevel' => (int)$xml->eventData->ccAgentJoinUpdateData->joinInfo->skillLevel,
            ];
            event(new CallCenterAgentEvent($ACDAgentJoinUpdateEvent));
            return $ACDAgentJoinUpdateEvent;
        } catch (\ErrorException $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ACDAgentJoinUpdateEvent');
        }
    }

    /*
        Parse Agent State Events
    */
    public function AgentStateEvent($xml)
    {
        try {
            $AgentStateEvent = [
                'eventType' => (string)'AgentStateEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'subscriptionId' => (string)$xml->subscriptionId,
                'targetId' => (string)$xml->targetId,
                'state' => (string)$xml->eventData->agentStateInfo->state,
                'stateTimestamp' => (int)$xml->eventData->agentStateInfo->stateTimestamp->value,
                'unavailableCode' => (int)$xml->eventData->agentStateInfo->unavailableCode,
                'signInTimestamp' => (int)$xml->eventData->agentStateInfo->signInTimestamp,
                'totalAvailableTime' => (int)$xml->eventData->agentStateInfo->totalAvailableTime,
                'averageWrapUpTime' => (int)$xml->eventData->agentStateInfo->averageWrapUpTime->value,
            ];
            event(new CallCenterAgentEvent($AgentStateEvent));
            return $AgentStateEvent;
        } catch (\ErrorException $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: AgentStateEvent');
        }
    }

    /*
        Parse Agent Subscription Events
    */
    public function AgentSubscriptionEvent($xml)
    {
        try {
            $callcenters = [];
            if (isset($xml->eventData)) {
                foreach ($xml->eventData->joinData->joinInfos as $callcenter) {
                    $acdUserId = (string)$callcenter->joinInfo->acdUserId;
                    // if (isset($callcenter->skillLevel)) {
                    //     $skillLevel = (int)$callcenter->skillLevel;
                    // } else {
                    //     $skillLevel = 0;
                    // }

                    array_push($callcenters, [
                                            'acdUserId' => $acdUserId
                                        ]);
                }
            }

            $AgentSubscriptionEvent = [
                'eventType' => (string)'AgentSubscriptionEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'subscriptionId' => (string)$xml->subscriptionId,
                'targetId' => (string)$xml->targetId,
                'callcenters' => $callcenters,
                'stateInfo' => (string) $xml->eventData->stateInfo->state,
                'stateIstateTimestampnfo' => (int) $xml->eventData->stateInfo->stateTimestamp->value
            ];
            event(new CallCenterAgentEvent($AgentSubscriptionEvent));
            return $AgentSubscriptionEvent;
        } catch (\ErrorException $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: AgentSubscriptionEvent');
        }
    }

    /*
        Parse Subscription Terminated Events
    */
    public function SubscriptionTerminatedEvent($xml)
    {
        try {
            $SubscriptionTerminatedEvent = [
                'eventType' => (string)'SubscriptionTerminatedEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'userId' => (string)$xml->userId,
                'subscriptionId' => (string)$xml->subscriptionId,
                'externalApplicationId' => (string)$xml->externalApplicationId,
                'httpContact' => (string)$xml->httpContact->uri,
            ];
            event(new CallCenterAgentEvent($SubscriptionTerminatedEvent));
            return $SubscriptionTerminatedEvent;
        } catch (\ErrorException $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: SubscriptionTerminatedEvent');
        }
    }

    public function SaveToDB($data)
    {
        // $this->CallCenterAgent->SaveToDB($data);
        // return response()->setStatusCode(200);
        return response('', 200);
    }
}
