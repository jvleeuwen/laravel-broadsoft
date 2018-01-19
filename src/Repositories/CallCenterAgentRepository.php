<?php

namespace Jvleeuwen\Broadsoft\Repositories;

use Jvleeuwen\Broadsoft\Models\CallCenterAgent;
use Jvleeuwen\Broadsoft\Contracts\CallCenterAgentContract;

class CallCenterAgentRepository implements CallCenterAgentContract
{
    public function SaveToDB($CallCenterAgentArray)
    {
        $eventType = (string)$CallCenterAgentArray['eventType'];
        $eventID = (string)$CallCenterAgentArray['eventID'];
        $sequenceNumber = (integer)$CallCenterAgentArray['sequenceNumber'];
        $subscriptionId = (string)$CallCenterAgentArray['subscriptionId'];
        $targetId = (string)$CallCenterAgentArray['targetId'];

        $ExistingCallCenterAgent = CallCenterAgent::where('targetId', $targetId)->first();
        if (!$ExistingCallCenterAgent) {
            $CallCenterAgent = new CallCenterAgent;
            $CallCenterAgent->eventType = $eventType;
            $CallCenterAgent->eventID = $eventID;
            $CallCenterAgent->sequenceNumber = $sequenceNumber;
            $CallCenterAgent->subscriptionId = $subscriptionId;
            $CallCenterAgent->targetID = $targetId;
            return $CallCenterAgent->save();
        } else {
            $ExistingCallCenterAgent->eventType = $eventType;
            $ExistingCallCenterAgent->eventID = $eventID;
            $ExistingCallCenterAgent->sequenceNumber = $sequenceNumber;
            $ExistingCallCenterAgent->subscriptionId = $subscriptionId;
            $ExistingCallCenterAgent->targetID = $targetId;
            return $ExistingCallCenterAgent->save();
        }
    }
}
