<?php

namespace Jvleeuwen\Broadsoft\Services;

// use PHPUnit\Framework\Exception;
// use Psy\Exception\ErrorException;
use Illuminate\Support\Facades\Log;
use Whoops\Exception\ErrorException;
use Jvleeuwen\Broadsoft\Events\Broadsoft\ErrorEvent;
use Jvleeuwen\Broadsoft\Contracts\AdvancedCallContract;
use Jvleeuwen\Broadsoft\Events\Broadsoft\AdvancedCallEvent;

class AdvancedCallService
{
    private $AdvancedCall;

    public function __construct(AdvancedCallContract $AdvancedCall)
    {
        $this->advancedcall = $AdvancedCall;
    }

    /*
        Get the event type from xml data
    */
    public function GetEventType($xml, $req)
    {

        try{
            $type = str_replace('xsi:', '', (string)$xml->eventData[0]->attributes('xsi1', true)->type);
        } catch(\ErrorException $e){
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return 'Can not detect the eventType';
        }

        try {
            return $this->$type($xml); // Call the type function like AgentStateEvent for further XML handling
        } catch(\Error $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return 'Function does not exist';
        }
    }

    /*
        Parse Call Answered Events
    */
    public function CallAnsweredEvent($xml)
    {
        try
        {
            $CallAnsweredEvent = array(
                "eventType" => (string)"CallAnsweredEvent",
                "eventID" => (string)$xml->eventID,
                "sequenceNumber" => (int)$xml->sequenceNumber,
                "subscriptionId" => (string)$xml->subscriptionId,
                "targetId" => (string)$xml->targetId,
                "callId" => (string)$xml->eventData->call->callId,
                "extTrackingId" => (string)$xml->eventData->call->extTrackingId,
                "personality" => (string)$xml->eventData->call->personality,
                "state" => (string)$xml->eventData->call->state,
                "remotePartyName" => (string)$xml->eventData->call->remoteParty->name,
                "remotePartyAddress" => (string)$xml->eventData->call->remoteParty->address,
                "remotePartyUserId" => (string)$xml->eventData->call->remoteParty->userId,
                "remotePartyUserDN" => (string)$xml->eventData->call->remoteParty->userDN,
                "remotePartyCallType" => (string)$xml->eventData->call->remoteParty->callType,
                "endpointAddressOfRecord" => (string)$xml->eventData->call->endpoint->addressOfRecord,
                "appearance" => (int)$xml->eventData->call->appearance,
                "startTime" => (int)$xml->eventData->call->startTime,
                "answerTime" => (int)$xml->eventData->call->answerTime,
                "allowedRecordingControls" => (string)$xml->eventData->call->allowedRecordingControls
            );
            // return $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallAnsweredEvent));
            return $CallAnsweredEvent;
        }
        catch(\Exception $e)
        {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallAnsweredEvent');
        }
    }

    /*
        Parse Call Barged In Events
    */
    public function CallBargedInEvent($xml)
    {
        try {
            $CallBargedInEvent = [
                    'eventType' => (string)'CallBargedInEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartyCallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'endpointAddressOfRecord' => (string)$xml->eventData->call->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->call->appearance,
                    'startTime' => (int)$xml->eventData->call->startTime,
                    'answerTime' => (int)$xml->eventData->call->answerTime,
                    'acdUserId' => (string)$xml->eventData->call->acdCallInfo->acdUserId,
                    'acdName' => (string)$xml->eventData->call->acdCallInfo->acdName,
                    'acdNumber' => (string)$xml->eventData->call->acdCallInfo->acdNumber,
                    'numCallsInQueue' => (int)$xml->eventData->call->acdCallInfo->numCallsInQueue,
                    'waitTime' => (int)$xml->eventData->call->acdCallInfo->waitTime,
                    'callingPartyInfoName' => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->name,
                    'callingPartyInfoAddress' => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->address,
                    'callingPartyInfoUserId' => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->userId,
                    'callingPartyInfoUserDN' => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->userDN,
                    'callingPartyInfoCallType' => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->callType,
                    'allowedRecordingControls' => (string)$xml->eventData->call->allowedRecordingControls,
                    'callType' => (string)$xml->eventData->call->recordingState
                ];
            event(new AdvancedCallEvent($CallBargedInEvent));
            return $CallBargedInEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallBargedInEvent');
        }
    }


    /*
        Parse Call Collecting Events
    */
    public function CallCollectingEvent($xml)
    {
        try {
            $CallCollectingEvent = [
                    'eventType' => (string)'CallCollectingEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartyCallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'endpointAddressOfRecord' => (string)$xml->eventData->call->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->call->appearance,
                    'startTime' => (int)$xml->eventData->call->startTime
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallCollectingEvent));
            return $CallCollectingEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallCollectingEvent');
        }
    }


    /*
        Parse Call Detached Events
        Deze functie is nog niet uitgewerkt, hier is geen bestaande xml van!
    */
    public function CallDetachedEvent($xml)
    {
        try {
            $CallDetachedEvent = [
                'eventType' => (string)'CallDetachedEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'subscriptionId' => (string)$xml->subscriptionId,
                'callId' => (string)$xml->eventData->call->callId,
                'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                'personality' => (string)$xml->eventData->call->personality,
                'state' => (string)$xml->eventData->call->state,
                'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                'remotePartyCallType' => (string)$xml->eventData->call->remoteParty->callType,
                'endpointAddressOfRecord' => (string)$xml->eventData->call->endpoint->addressOfRecord,
                'appearance' => (int)$xml->eventData->call->appearance,
                'startTime' => (int)$xml->eventData->call->startTime,
                'answerTime' => (int)$xml->eventData->call->answerTime,
                'detachedTime' => (int)$xml->eventData->call->detachedTime,
            ];
            event(new AdvancedCallEvent($CallDetachedEvent));
            return $CallDetachedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallDetachedEvent');
        }
    }

    /*
       Parse Call Forwarded Events
    */
    public function CallForwardedEvent($xml)
    {
        try {
            $CallForwardedEvent = [
                    'eventType' => (string)'CallForwardedEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartyCallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'endpointAddressOfRecord' => (string)$xml->eventData->call->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->call->appearance,
                    'startTime' => (int)$xml->eventData->call->startTime
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallForwardedEvent));
            return $CallForwardedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallForwardedEvent');
        }
    }


    /*
       Parse Call Held Events
    */
    public function CallHeldEvent($xml)
    {
        try {
            if (isset($xml->eventData->call->acdCallInfo->callingPartyInfo->name)) {
                $callingPartyInfoName = (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->name;
            }
            if (isset($xml->eventData->call->acdCallInfo->callingPartyInfo->address)) {
                $callingPartyInfoAddress = (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->address;
            }
            if (isset($xml->eventData->call->acdCallInfo->callingPartyInfo->callType)) {
                $callingPartyInfoCallType = (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->callType;
            }
            if (isset($xml->eventData->call->acdCallInfo->numCallsInQueue)) {
                $numCallsInQueue = (int)$xml->eventData->call->acdCallInfo->numCallsInQueue;
            }
            $CallHeldEvent = [
                    'eventType' => (string)'CallHeldEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartyCallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'endpointAddressOfRecord' => (string)$xml->eventData->call->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->call->appearance,
                    'startTime' => (int)$xml->eventData->call->startTime,
                    'answerTime' => (int)$xml->eventData->call->answerTime,
                    'heldTime' => (int)$xml->eventData->call->heldTime,
                    'acdUserId' => (string)$xml->eventData->call->acdCallInfo->acdUserId,
                    'acdName' => (string)$xml->eventData->call->acdCallInfo->acdName,
                    'acdNumber' => (string)$xml->eventData->call->acdCallInfo->acdNumber,
                    'numCallsInQueue' => $numCallsInQueue ?? '',
                    'waitTime' => (int)$xml->eventData->call->acdCallInfo->waitTime,
                    'callingPartyInfoName' => $callingPartyInfoName ?? '',
                    'callingPartyInfoAddress' => $callingPartyInfoAddress ?? '',
                    'callingPartyInfoCallType' => $callingPartyInfoCallType ?? '',
                    'allowedRecordingControls' => (string)$xml->eventData->call->allowedRecordingControls,
                    'recordingState' => (string)$xml->eventData->call->recordingState
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallHeldEvent));
            return $CallHeldEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallHeldEvent');
        }
    }


    /*
       Parse Call Originated Events
    */
    public function CallOriginatedEvent($xml)
    {
        try {
            $CallOriginatedEvent = [
                    'eventType' => (string)'CallOriginatedEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartyCallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'endpointAddressOfRecord' => (string)$xml->eventData->call->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->call->appearance,
                    'startTime' => (int)$xml->eventData->call->startTime
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallOriginatedEvent));
            return $CallOriginatedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallOriginatedEvent');
        }
    }


    /*
       Parse Call Originated Events
    */
    public function CallOriginatingEvent($xml)
    {
        try {
            $CallOriginatingEvent = [
                    'eventType' => (string)'CallOriginatingEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartyCallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'endpointAddressOfRecord' => (string)$xml->eventData->call->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->call->appearance,
                    'startTime' => (int)$xml->eventData->call->startTime
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallOriginatingEvent));
            return $CallOriginatingEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallOriginatingEvent');
        }
    }


    /*
       Parse Call Picked Up Events
    */
    public function CallPickedUpEvent($xml)
    {
        try {
            $CallPickedUpEvent = [
                'eventType' => (string)'CallPickedUpEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'subscriptionId' => (string)$xml->subscriptionId,
                'targetId' => (string)$xml->targetId,
                'callId' => (string)$xml->eventData->call->callId,
                'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                'personality' => (string)$xml->eventData->call->personality,
                'state' => (string)$xml->eventData->call->state,
                'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                'remotePartyCallType' => (string)$xml->eventData->call->remoteParty->callType,
                'endpointAddressOfRecord' => (string)$xml->eventData->call->endpoint->addressOfRecord,
                'appearance' => (int)$xml->eventData->call->appearance,
                'startTime' => (int)$xml->eventData->call->startTime,
                'answerTime' => (int)$xml->eventData->call->answerTime,
                'allowedRecordingControls' => (string)$xml->eventData->call->allowedRecordingControls
            ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallPickedUpEvent));
            return $CallPickedUpEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallPickedUpEvent');
        }
    }


    /*
       Parse Call Received Events
    */
    public function CallReceivedEvent($xml)
    {
        try {
            $redirection = [];
            if (isset($xml->eventData->call->redirections->redirection)) {
                $redirections = $xml->eventData->call->redirections->redirection;

                foreach ($redirections as $red) {
                    $name = (string)$red->party->name;
                    $address = (string)$red->party->address;
                    $userId = (string)$red->party->userId;
                    $callType = (string)$red->party->callType;
                    $reason = (string)$red->reason;
                    array_push($redirection, [
                            'name' => $name,
                            'address' => $address,
                            'userId' => $userId,
                            'callType' => $callType,
                            'reason' => $reason
                        ]);
                }
            }

            if (isset($xml->eventData->call->acdCallInfo)) {
                if (isset($xml->eventData->call->acdCallInfo->callingPartyInfo)) {
                    $callingPartyInfoName = (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->name;
                    $callingPartyInfoAddress = (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->address;
                    $callingPartyInfoCallType = (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->callType;
                }
            } else {
                $callingPartyInfoName = '';
                $callingPartyInfoAddress = '';
                $callingPartyInfoCallType = '';
            }

            $CallReceivedEvent = [
                    'eventType' => (string)'CallReceivedEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartyCallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'redirections' => $redirection,
                    'startTime' => (int)$xml->eventData->call->startTime,
                    'acdUserId' => (string)$xml->eventData->call->acdCallInfo->acdUserId,
                    'acdName' => (string)$xml->eventData->call->acdCallInfo->acdName,
                    'acdNumber' => (string)$xml->eventData->call->acdCallInfo->acdNumber,
                    'numCallsInQueue' => (int)$xml->eventData->call->acdCallInfo->numCallsInQueue,
                    'waitTime' => (int)$xml->eventData->call->acdCallInfo->waitTime,
                    'callingPartyInfoName' => (string)$callingPartyInfoName,
                    'callingPartyInfoAddress' => (string)$callingPartyInfoAddress,
                    'callingPartyInfoCallType' => (string)$callingPartyInfoCallType
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallReceivedEvent));
            return $CallReceivedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallReceivedEvent');
        }
    }


    /*
       Parse Call Recording Started Events
    */
    public function CallRecordingStartedEvent($xml)
    {
        try {
            if (isset($xml->eventData->call->acdCallInfo->callingPartyInfo->name)) {
                $callingPartyInfoName = (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->name;
            }
            if (isset($xml->eventData->call->acdCallInfo->callingPartyInfo->address)) {
                $callingPartyInfoAddress = (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->address;
            }
            if (isset($xml->eventData->call->acdCallInfo->acdNumber)) {
                $acdName = (string)$xml->eventData->call->acdCallInfo->acdNumber;
            }
            if (isset($xml->eventData->call->acdCallInfo->callingPartyInfo->callType)) {
                $callingPartyInfoCallType = (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->callType;
            }

            $CallRecordingStartedEvent = [
                    'eventType' => (string)'CallRecordingStartedEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartyCallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'startTime' => (int)$xml->eventData->call->startTime,
                    'answerTime' => (int)$xml->eventData->call->answerTime,
                    'acdUserId' => (string)$xml->eventData->call->acdCallInfo->acdUserId,
                    'acdName' => (string)$xml->eventData->call->acdCallInfo->acdName,
                    'acdNumber' => $acdName ?? '',
                    'numCallsInQueue' => (int)$xml->eventData->call->acdCallInfo->numCallsInQueue,
                    'waitTime' => (int)$xml->eventData->call->acdCallInfo->waitTime,
                    'callingPartyInfoName' => $callingPartyInfoName ?? '',
                    'callingPartyInfoAddress' => $callingPartyInfoAddress ?? '',
                    'callingPartyInfoCallType' => $callingPartyInfoCallType ?? '',
                    'allowedRecordingControls' => (string)$xml->eventData->call->allowedRecordingControls,
                    'recordingState' => (string)$xml->eventData->call->recordingState
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallRecordingStartedEvent));
            return $CallRecordingStartedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallRecordingStartedEvent');
        }
    }


    /*
       Parse Call Recording Stopped Events
    */
    public function CallRecordingStoppedEvent($xml)
    {
        try {
            $CallRecordingStoppedEvent = [
                    'eventType' => (string)'CallRecordingStoppedEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartyCallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'appearance' => (int)$xml->eventData->call->appearance,
                    'startTime' => (int)$xml->eventData->call->startTime,
                    'answerTime' => (int)$xml->eventData->call->answerTime,
                    'acdUserId' => (string)$xml->eventData->call->acdCallInfo->acdUserId,
                    'acdName' => (string)$xml->eventData->call->acdCallInfo->acdName,
                    'acdNumber' => (string)$xml->eventData->call->acdCallInfo->acdNumber,
                    'numCallsInQueue' => (int)$xml->eventData->call->acdCallInfo->numCallsInQueue,
                    'waitTime' => (int)$xml->eventData->call->acdCallInfo->waitTime,
                    'allowedRecordingControls' => (string)$xml->eventData->call->allowedRecordingControls,
                    'recordingState' => (string)$xml->eventData->call->recordingState,
                    'reason' => (string)$xml->eventData->reason
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallRecordingStoppedEvent));
            return $CallRecordingStoppedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallRecordingStoppedEvent');
        }
    }


    /*
       Parse Call Redirected Events
    */
    public function CallRedirectedEvent($xml)
    {
        try {
            $calllist = [];
            $calls = $xml->eventData->calls->call;

            foreach ($calls as $call) {
                $callId = (string)$call->callId;
                $extTrackingId = (string)$call->extTrackingId;
                $personality = (string)$call->personality;
                $state = (string)$call->state;
                $remotePartyName = (string)$call->remoteParty->name;
                $remotePartyAddress = (string)$call->remoteParty->address;
                $remotePartyUserId = (string)$call->remoteParty->userId;
                $remotePartyUserDN = (string)$call->remoteParty->userDN;
                $remotePartyCallType = (string)$call->remoteParty->callType;
                $redirectAddress = (string)$call->redirect->address;
                $redirectReason = (string)$call->redirect->reason;
                $redirectTime = (int)$call->redirect->redirectTime;
                $startTime = (int)$call->startTime;
                $answerTime = (int)$call->answerTime;
                $totalHeldTime = (int)$call->totalHeldTime;
                $detachedTime = (int)$call->detachedTime;
                $allowedRecordingControls = (string)$call->allowedRecordingControls;

                array_push($calllist, [
                        'callId' => $callId,
                        'extTrackingId' => $extTrackingId,
                        'personality' => $personality,
                        'state' => $state,
                        'remotePartyName' => $remotePartyName,
                        'remotePartyAddress' => $remotePartyAddress,
                        'remotePartyUserId' => $remotePartyUserId,
                        'remotePartyUserDN' => $remotePartyUserDN,
                        'remotePartyCallType' => $remotePartyCallType,
                        'redirectAddress' => $redirectAddress,
                        'redirectReason' => $redirectReason,
                        'redirectTime' => $redirectTime,
                        'startTime' => $startTime,
                        'answerTime' => $answerTime,
                        'totalHeldTime' => $totalHeldTime,
                        'detachedTime' => $detachedTime,
                        'allowedRecordingControls' => $allowedRecordingControls
                    ]);
            }
            $CallRedirectedEvent = [
                    'eventType' => (string)'CallRedirectedEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'calls' => $calllist
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state); // is volgens mij hier niet nodig.
            event(new AdvancedCallEvent($CallRedirectedEvent));
            return $CallRedirectedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallRedirectedEvent');
        }
    }


    /*
       Parse Call Released Events
    */
    public function CallReleasedEvent($xml)
    {
        try {
            $CallReleasedEvent = [
                    'eventType' => (string)'CallReleasedEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'releasingParty' => (string)$xml->eventData->call->releasingParty,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartycallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'startTime' => (int)$xml->eventData->call->startTime,
                    'answerTime' => (int)$xml->eventData->call->answerTime,
                    'releaseTime' => (int)$xml->eventData->call->releaseTime,
                    'allowedRecordingControls' => (string)$xml->eventData->call->allowedRecordingControls
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallReleasedEvent));
            return $CallReleasedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallReleasedEvent');
        }
    }


    /*
       Parse Call Releasing Events
    */
    public function CallReleasingEvent($xml)
    {
        try {
            $CallReleasingEvent = [
                    'eventType' => (string)'CallReleasingEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'releaseCauseInternalReleaseCause' => (string)$xml->eventData->call->releaseCause->internalReleaseCause,
                    'releaseCauseCdrTerminationCause' => (int)$xml->eventData->call->releaseCause->cdrTerminationCause,
                    'releasingParty' => (string)$xml->eventData->call->releasingParty,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartycallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'addressOfRecord' => (string)$xml->eventData->call->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->call->appearance,
                    'startTime' => (int)$xml->eventData->call->startTime
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallReleasingEvent));
            return $CallReleasingEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallReleasingEvent');
        }
    }


    /*
       Parse Call Retrieved Events
    */
    public function CallRetrievedEvent($xml)
    {
        try {
            if (isset($xml->eventData->call->acdCallInfo->callingPartyInfo->name)) {
                $callingPartyInfoName = (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->name;
            }
            if (isset($xml->eventData->call->acdCallInfo->acdNumber)) {
                $acdNumber = (string)$xml->eventData->call->acdCallInfo->acdNumber;
            }
            if (isset($xml->eventData->call->acdCallInfo->acdName)) {
                $acdName = (string)$xml->eventData->call->acdCallInfo->acdName;
            }
            if (isset($xml->eventData->call->acdCallInfo->callingPartyInfo->address)) {
                $callingPartyInfoAddress = (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->address;
            }
            if (isset($xml->eventData->call->acdCallInfo->callingPartyInfo->callType)) {
                $callingPartyInfoCallType = (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->callType;
            }
            $CallRetrievedEvent = [
                    'eventType' => (string)'CallRetrievedEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartycallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'addressOfRecord' => (string)$xml->eventData->call->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->call->appearance,
                    'startTime' => (int)$xml->eventData->call->startTime,
                    'answerTime' => (int)$xml->eventData->call->answerTime,
                    'totalHeldTime' => (int)$xml->eventData->call->totalHeldTime,
                    'acdUserId' => (string)$xml->eventData->call->acdCallInfo->acdUserId,
                    'acdName' => $acdName ?? '',
                    'acdNumber' => $acdNumber ?? '',
                    'numCallsInQueue' => (int)$xml->eventData->call->acdCallInfo->numCallsInQueue,
                    'waitTime' => (int)$xml->eventData->call->acdCallInfo->waitTime,
                    'callingPartyInfoName' => $callingPartyInfoName ?? '',
                    'callingPartyInfoAddress' => $callingPartyInfoAddress ?? '',
                    'callingPartyInfoCallType' => $callingPartyInfoCallType ?? '',
                    'allowedRecordingControls' => (string)$xml->eventData->call->allowedRecordingControls,
                    'recordingState' => (string)$xml->eventData->call->recordingState
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallRetrievedEvent));
            return $CallRetrievedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallRetrievedEvent');
        }
    }


    /*
       Parse Call Subscription Events
    */
    public function CallSubscriptionEvent($xml)
    {
        try {
            $CallSubscriptionEvent = [
                    'eventType' => (string)'CallSubscriptionEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'hookStatus' => (string)$xml->eventData->hookStatus
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->hookStatus);
            event(new AdvancedCallEvent($CallSubscriptionEvent));
            return $CallSubscriptionEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallSubscriptionEvent');
        }
    }


    /*
       Parse Call Transferred Events
    */
    public function CallTransferredEvent($xml)
    {
        try {
            $CallTransferredEvent = [
                    'eventType' => (string)'CallTransferredEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartycallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'address' => (string)$xml->eventData->call->endpoint->address,
                    'appearance' => (int)$xml->eventData->call->appearance,
                    'startTime' => (int)$xml->eventData->call->startTime,
                    'answerTime' => (int)$xml->eventData->call->answerTime
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallTransferredEvent));
            return $CallTransferredEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallTransferredEvent');
        }
    }


    /*
       Parse Call Updated Events
    */
    public function CallUpdatedEvent($xml)
    {
        try {
            $CallUpdatedEvent = [
                    'eventType' => (string)'CallUpdatedEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'callId' => (string)$xml->eventData->call->callId,
                    'extTrackingId' => (string)$xml->eventData->call->extTrackingId,
                    'personality' => (string)$xml->eventData->call->personality,
                    'state' => (string)$xml->eventData->call->state,
                    'remotePartyName' => (string)$xml->eventData->call->remoteParty->name,
                    'remotePartyAddress' => (string)$xml->eventData->call->remoteParty->address,
                    'remotePartyUserId' => (string)$xml->eventData->call->remoteParty->userId,
                    'remotePartyUserDN' => (string)$xml->eventData->call->remoteParty->userDN,
                    'remotePartycallType' => (string)$xml->eventData->call->remoteParty->callType,
                    'addressOfRecord' => (string)$xml->eventData->call->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->call->appearance,
                    'startTime' => (int)$xml->eventData->call->startTime
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state);
            event(new AdvancedCallEvent($CallUpdatedEvent));
            return $CallUpdatedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: CallUpdatedEvent');
        }
    }


    /*
       Parse Conference Held Events
    */
    public function ConferenceHeldEvent($xml)
    {
        try {
            $ConferenceHeldEvent = [
                    'eventType' => (string)'ConferenceHeldEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'state' => (string)$xml->eventData->conference->state,
                    'addressOfRecord' => (string)$xml->eventData->conference->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->conference->appearance
                ];
            event(new AdvancedCallEvent($ConferenceHeldEvent));
            return $ConferenceHeldEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ConferenceHeldEvent');
        }
    }


    /*
       Parse Conference Released Events
    */
    public function ConferenceReleasedEvent($xml)
{
    try {
        $ConferenceReleasedEvent = [
                'eventType' => (string)'ConferenceReleasedEvent',
                'eventID' => (string)$xml->eventID,
                'sequenceNumber' => (int)$xml->sequenceNumber,
                'subscriptionId' => (string)$xml->subscriptionId,
                'targetId' => (string)$xml->targetId,
                'state' => (string)$xml->eventData->conference->state
            ];
        // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state); // is enkel een status voor conf calls
        event(new AdvancedCallEvent($ConferenceReleasedEvent));
        return $ConferenceReleasedEvent;
    } catch (\Exception $e) {
        Log::error($e);
        event(new ErrorEvent(['error' => (string)$e]));
        return ('can not parse event: ConferenceReleasedEvent');
    }
}


    /*
       Parse Conference Retrieved Events
    */
    public function ConferenceRetrievedEvent($xml)
    {
        try {
            $conferenceParticipantList = [];
            $confParticipantList = $xml->eventData->conference->conferenceParticipantList->conferenceParticipant;

            foreach ($confParticipantList as $participant) {
                $callId = (string)$participant->callId;
                array_push($conferenceParticipantList, ['callId' => $callId]);
            }

            $ConferenceRetrievedEvent = [
                    'eventType' => (string)'ConferenceRetrievedEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'state' => (string)$xml->eventData->conference->state,
                    'addressOfRecord' => (string)$xml->eventData->conference->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->conference->appearance,
                    'conferenceParticipantList' => $conferenceParticipantList
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state); // is enkel een status voor conf calls
            event(new AdvancedCallEvent($ConferenceRetrievedEvent));
            return $ConferenceRetrievedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ConferenceRetrievedEvent');
        }
    }


    /*
       Parse Conference Started Events
    */
    public function ConferenceStartedEvent($xml)
    {
        try {
            $conferenceParticipantList = [];
            $confParticipantList = $xml->eventData->conference->conferenceParticipantList->conferenceParticipant;

            foreach ($confParticipantList as $participant) {
                $callId = (string)$participant->callId;
                array_push($conferenceParticipantList, ['callId' => $callId]);
            }

            $ConferenceStartedEvent = [
                    'eventType' => (string)'ConferenceStartedEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'state' => (string)$xml->eventData->conference->state,
                    'addressOfRecord' => (string)$xml->eventData->conference->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->conference->appearance,
                    'conferenceType' => (string)$xml->eventData->conference->conferenceType,
                    'conferenceParticipantList' => $conferenceParticipantList
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state); // is enkel een status voor conf calls
            event(new AdvancedCallEvent($ConferenceStartedEvent));
            return $ConferenceStartedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ConferenceStartedEvent');
        }
    }


    /*
       Parse Conference Updated Events
    */
    public function ConferenceUpdatedEvent($xml)
    {
        try {
            $conferenceParticipantList = [];
            $confParticipantList = $xml->eventData->conference->conferenceParticipantList->conferenceParticipant;

            foreach ($confParticipantList as $participant) {
                $callId = (string)$participant->callId;
                array_push($conferenceParticipantList, ['callId' => $callId]);
            }

            $ConferenceUpdatedEvent = [
                    'eventType' => (string)'ConferenceUpdatedEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'state' => (string)$xml->eventData->conference->state,
                    'addressOfRecord' => (string)$xml->eventData->conference->endpoint->addressOfRecord,
                    'appearance' => (int)$xml->eventData->conference->appearance,
                    'conferenceParticipantList' => $conferenceParticipantList
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->call->state); // is enkel een conf call status
            event(new AdvancedCallEvent($ConferenceUpdatedEvent));
            return $ConferenceUpdatedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: ConferenceUpdatedEvent');
        }
    }


    /*
       Parse Hook Status Events
    */
    public function HookStatusEvent($xml)
    {
        try {
            $HookStatusEvent = [
                    'eventType' => (string)'HookStatusEvent',
                    'eventID' => (string)$xml->eventID,
                    'sequenceNumber' => (int)$xml->sequenceNumber,
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'state' => (string)$xml->eventData->hookStatus
                ];
            // $this->bsUser->SetHookState((string)$xml->targetId, (string)$xml->eventData->hookStatus);
            event(new AdvancedCallEvent($HookStatusEvent));
            return $HookStatusEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: HookStatusEvent');
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
                    'subscriptionId' => (string)$xml->subscriptionId,
                    'targetId' => (string)$xml->targetId,
                    'httpContactUri' => (string)$xml->httpContact->uri
                ];
            event(new AdvancedCallEvent($SubscriptionTerminatedEvent));
            return $SubscriptionTerminatedEvent;
        } catch (\Exception $e) {
            Log::error($e);
            event(new ErrorEvent(['error' => (string)$e]));
            return ('can not parse event: SubscriptionTerminatedEvent');
        }
    }


    // public function SaveToDB($data)
    // {
    //     $this->AdvancedCall->SaveToDB($data);
    // }
}
