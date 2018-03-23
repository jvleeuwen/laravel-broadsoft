<?php

namespace Jvleeuwen\Broadsoft\Services;

use Jvleeuwen\Broadsoft\Events\Broadsoft\ErrorEvent;
use Jvleeuwen\Broadsoft\Contracts\CallCenterQueueContract;
use Jvleeuwen\Broadsoft\Events\Broadsoft\CallCenterQueueEvent;

class CallCenterQueueService
{
    private $CallCenterQueue;

    public function __construct(CallCenterQueueContract $CallCenterQueue)
    {
        $this->CallCenterQueue = $CallCenterQueue;
    }

    /*
        Get the event type from xml data
    */
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
        Parse ACD Call Abandoned Events
    */
    public function ACDCallAbandonedEvent($xml)
    {
        try {
            $ACDCallAbandonedEvent = [
                'eventType' => (string)'ACDCallAbandonedEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'subscriptionId' => (string)$xml->subscriptionId,
                'targetId' => (string)$xml->targetId,
                'callId' => (string)$xml->eventData->queueEntry->callId,
                'extTrackingId' => (string)$xml->eventData->queueEntry->extTrackingId,
                'remotePartyName' => (string)$xml->eventData->queueEntry->remoteParty->name,
                'remotePartyAddress' => (string)$xml->eventData->queueEntry->remoteParty->address,
                'addTime' => (int)$xml->eventData->queueEntry->addTime,
                'removeTime' => (int)$xml->eventData->queueEntry->removeTime,
                'acdName' => (string)$xml->eventData->queueEntry->acdName,
                'acdNumber' => (string)$xml->eventData->queueEntry->acdNumber
            ];
            event(new CallCenterQueueEvent($ACDCallAbandonedEvent));
            return $ACDCallAbandonedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ACDCallAbandonedEvent');
        }
    }

    /*
        Parse ACD Call Added Events
    */
    public function ACDCallAddedEvent($xml)
    {
        try {
            $ACDCallAddedEvent = [
                'eventType' => (string)'ACDCallAddedEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'subscriptionId' => (string)$xml->subscriptionId,
                'targetId' => (string)$xml->targetId,
                'callId' => (string)$xml->eventData->queueEntry->callId,
                'extTrackingId' => (string)$xml->eventData->queueEntry->extTrackingId,
                'remotePartyName' => (string)$xml->eventData->queueEntry->remoteParty->name,
                'remotePartyAddress' => (string)$xml->eventData->queueEntry->remoteParty->address,
                'addTime' => (int)$xml->eventData->queueEntry->addTime,
                'acdName' => (string)$xml->eventData->queueEntry->acdName,
                'acdNumber' => (string)$xml->eventData->queueEntry->acdNumber,
                'acdPriority' => (string)$xml->eventData->queueEntry->acdPriority,
                'addTimeInPriorityBucket' => (int)$xml->eventData->queueEntry->addTimeInPriorityBucket,
                'position' => (int)$xml->eventData->position
            ];
            event(new CallCenterQueueEvent($ACDCallAddedEvent));
            return $ACDCallAddedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ACDCallAddedEvent');
        }
    }

    /*
        Parse ACD Call Answered By Agent Events
    */
    public function ACDCallAnsweredByAgentEvent($xml)
    {
        try {
            $ACDCallAnsweredByAgentEvent = [
                'eventType' => (string)'ACDCallAnsweredByAgentEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'subscriptionId' => (string)$xml->subscriptionId,
                'targetId' => (string)$xml->targetId,
                'callId' => (string)$xml->eventData->queueEntry->callId,
                'extTrackingId' => (string)$xml->eventData->queueEntry->extTrackingId,
                'remotePartyName' => (string)$xml->eventData->queueEntry->remoteParty->name,
                'remotePartyAddress' => (string)$xml->eventData->queueEntry->remoteParty->address,
                'addTime' => (int)$xml->eventData->queueEntry->addTime,
                'removeTime' => (int)$xml->eventData->queueEntry->removeTime,
                'acdName' => (string)$xml->eventData->queueEntry->acdName,
                'acdNumber' => (string)$xml->eventData->queueEntry->acdNumber,
                'acdPriority' => (string)$xml->eventData->queueEntry->acdPriority,
                'addTimeInPriorityBucket' => (int)$xml->eventData->queueEntry->addTimeInPriorityBucket,
                'positiansweringUserIdon' => (string)$xml->eventData->queueEntry->answeringUserId,
                'answeringCallId' => (string)$xml->eventData->queueEntry->answeringCallId
            ];
            event(new CallCenterQueueEvent($ACDCallAnsweredByAgentEvent));
            return $ACDCallAnsweredByAgentEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ACDCallAnsweredByAgentEvent');
        }
    }

    /*
        Parse ACD Call Bounced Events
    */
    public function ACDCallBouncedEvent($xml)
    {
        try {
            $ACDCallBouncedEvent = [
                'eventType' => (string)'ACDCallBouncedEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'subscriptionId' => (string)$xml->subscriptionId,
                'targetId' => (string)$xml->targetId,
                'callId' => (string)$xml->eventData->queueEntry->callId,
                'extTrackingId' => (string)$xml->eventData->queueEntry->extTrackingId,
                'remotePartyName' => (string)$xml->eventData->queueEntry->remoteParty->name,
                'remotePartyAddress' => (string)$xml->eventData->queueEntry->remoteParty->address,
                'addTime' => (int)$xml->eventData->queueEntry->addTime,
                'acdName' => (string)$xml->eventData->queueEntry->acdName,
                'acdNumber' => (string)$xml->eventData->queueEntry->acdNumber,
                'position' => (int)$xml->eventData->position
            ];
            event(new CallCenterQueueEvent($ACDCallBouncedEvent));
            return $ACDCallBouncedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ACDCallBouncedEvent');
        }
    }

    /*
        Parse ACD Call Offered To Agent Events
    */
    public function ACDCallOfferedToAgentEvent($xml)
    {
        try {
            $ACDCallOfferedToAgentEvent = [
                'eventType' => (string)'ACDCallOfferedToAgentEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'subscriptionId' => (string)$xml->subscriptionId,
                'targetId' => (string)$xml->targetId,
                'callId' => (string)$xml->eventData->queueEntry->callId,
                'extTrackingId' => (string)$xml->eventData->queueEntry->extTrackingId,
                'remotePartyName' => (string)$xml->eventData->queueEntry->remoteParty->name,
                'remotePartyAddress' => (string)$xml->eventData->queueEntry->remoteParty->address,
                'addTime' => (int)$xml->eventData->queueEntry->addTime,
                'acdName' => (string)$xml->eventData->queueEntry->acdName,
                'acdNumber' => (string)$xml->eventData->queueEntry->acdNumber,
                'acdPriority' => (string)$xml->eventData->queueEntry->acdPriority,
                'addTimeInPriorityBucket' => (int)$xml->eventData->queueEntry->addTimeInPriorityBucket
            ];
            event(new CallCenterQueueEvent($ACDCallOfferedToAgentEvent));
            return $ACDCallOfferedToAgentEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ACDCallOfferedToAgentEvent');
        }
    }

    /*
        Parse ACD Call Overflowed Events
    */
    public function ACDCallOverflowedEvent($xml)
    {
        try {
            $ACDCallOverflowedEvent = [
                'eventType' => (string)'ACDCallOverflowedEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'subscriptionId' => (string)$xml->subscriptionId,
                'targetId' => (string)$xml->targetId,
                'callId' => (string)$xml->eventData->queueEntry->callId,
                'extTrackingId' => (string)$xml->eventData->queueEntry->extTrackingId,
                'remotePartyName' => (string)$xml->eventData->queueEntry->remoteParty->name,
                'remotePartyAddress' => (string)$xml->eventData->queueEntry->remoteParty->address,
                'addTime' => (int)$xml->eventData->queueEntry->addTime,
                'removeTime' => (int)$xml->eventData->queueEntry->removeTime,
                'acdName' => (string)$xml->eventData->queueEntry->acdName,
                'acdNumber' => (string)$xml->eventData->queueEntry->acdNumber,
                'acdPriority' => (string)$xml->eventData->queueEntry->acdPriority,
                'addTimeInPriorityBucket' => (int)$xml->eventData->queueEntry->addTimeInPriorityBucket,
                'redirectAddress' => (string)$xml->eventData->redirect->address,
                'redirectReason' => (string)$xml->eventData->redirect->reason,
                'redirectTime' => (int)$xml->eventData->redirect->redirectTime
            ];
            event(new CallCenterQueueEvent($ACDCallOverflowedEvent));
            return $ACDCallOverflowedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ACDCallOverflowedEvent');
        }
    }

    /*
        Parse ACD Call Overflowed Treatment Completed Events
    */
    public function ACDCallOverflowedTreatmentCompletedEvent($xml)
    {
        try {
            $ACDCallOverflowedTreatmentCompletedEvent = [
                "eventType" => (string)"ACDCallOverflowedTreatmentCompletedEvent",
                "eventID" => (string)$xml->eventID,
                "sequenceNumber" => (int)$xml->sequenceNumber,
                "subscriptionId" => (string)$xml->subscriptionId,
                "targetId" => (string)$xml->targetId,
                "callId" => (string)$xml->eventData->queueEntry->callId,
                "extTrackingId" => (string)$xml->eventData->queueEntry->extTrackingId,
                "remotePartyName" => (string)$xml->eventData->queueEntry->remoteParty->name,
                "remotePartyAddress" => (string)$xml->eventData->queueEntry->remoteParty->address,
                "addTime" => (int)$xml->eventData->queueEntry->addTime,
                "removeTime" => (int)$xml->eventData->queueEntry->removeTime,
                "acdName" => (string)$xml->eventData->queueEntry->acdName,
                "acdNumber" => (string)$xml->eventData->queueEntry->acdNumber,
                "acdPriority" => (string)$xml->eventData->queueEntry->acdPriority,
                "addTimeInPriorityBucket" => (int)$xml->eventData->queueEntry->addTimeInPriorityBucket,
                "overflowReason" => (string)$xml->eventData->overflowReason,
                "reason" => (string)$xml->eventData->reason
            ];
            event(new CallCenterQueueEvent($ACDCallOverflowedTreatmentCompletedEvent));
            return $ACDCallOverflowedTreatmentCompletedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ACDCallOverflowedTreatmentCompletedEvent');
        }
    }

    /*
        Parse ACD Call Transferred Events
    */
    public function ACDCallTransferredEvent($xml)
    {
        try {
            $ACDCallTransferredEvent = [
                "eventType" => (string)"ACDCallTransferredEvent",
                "eventID" => (string)$xml->eventID,
                "sequenceNumber" => (int)$xml->sequenceNumber,
                "subscriptionId" => (string)$xml->subscriptionId,
                "targetId" => (string)$xml->targetId,
                "callId" => (string)$xml->eventData->queueEntry->callId,
                "extTrackingId" => (string)$xml->eventData->queueEntry->extTrackingId,
                "remotePartyName" => (string)$xml->eventData->queueEntry->remoteParty->name,
                "remotePartyAddress" => (string)$xml->eventData->queueEntry->remoteParty->address,
                "addTime" => (int)$xml->eventData->queueEntry->addTime,
                "removeTime" => (int)$xml->eventData->queueEntry->removeTime,
                "acdName" => (string)$xml->eventData->queueEntry->acdName,
                "acdNumber" => (string)$xml->eventData->queueEntry->acdNumber,
                "redirectAddress" => (string)$xml->eventData->redirect->address,
                "redirectReason" => (string)$xml->eventData->redirect->reason,
                "redirectTime" => (string)$xml->eventData->redirect->redirectTime
            ];
            event(new CallCenterQueueEvent($ACDCallTransferredEvent));
            return $ACDCallTransferredEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ACDCallTransferredEvent');
        }
    }

    /*
        Parse ACD Call Stranded Events
    */
    public function ACDCallStrandedEvent($xml)
    {
        try {
            $ACDCallStrandedEvent = [
                "eventType" => (string)"ACDCallTransferredEvent",
                "eventID" => (string)$xml->eventID,
                "sequenceNumber" => (int)$xml->sequenceNumber,
                "subscriptionId" => (string)$xml->subscriptionId,
                "targetId" => (string)$xml->targetId,
                "callId" => (string)$xml->eventData->queueEntry->callId,
                "extTrackingId" => (string)$xml->eventData->queueEntry->extTrackingId,
                "remotePartyName" => (string)$xml->eventData->queueEntry->remoteParty->name,
                "remotePartyAddress" => (string)$xml->eventData->queueEntry->remoteParty->address,
                "addTime" => (int)$xml->eventData->queueEntry->addTime,
                "removeTime" => (int)$xml->eventData->queueEntry->removeTime,
                "acdName" => (string)$xml->eventData->queueEntry->acdName,
                "acdNumber" => (string)$xml->eventData->queueEntry->acdNumber
            ];
            event(new CallCenterQueueEvent($ACDCallStrandedEvent));
            return $ACDCallStrandedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ACDCallStrandedEvent');
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
            event(new CallCenterQueueEvent($SubscriptionTerminatedEvent));
            return $SubscriptionTerminatedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: SubscriptionTerminatedEvent');
        }
    }

    public function SaveToDB($data)
    {
        $this->CallCenterQueue->SaveToDB($data);
    }
}
