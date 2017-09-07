<?php

namespace jvleeuwen\broadsoft\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use jvleeuwen\broadsoft\Events\CallCenterAgentEvent;

class AdvancedCallController extends Controller
{
    /*
        Handle incomming XML messages for the Call Center Agent
    */
    public function Incomming(Request $request)
    {
    	$req = $request->getContent();
        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        return $this->GetEventType($xml);

    }

    /*
        Get the event type from xml data
    */
    protected function GetEventType($xml)
    {
        $type = str_replace('xsi:','', (string)$xml->eventData[0]->attributes('xsi1', true)->type);

        try {
            return $this->$type($xml); # Call the type function like AgentStateEvent for further XML handling    
        }
        catch(\BadMethodCallException $e)
        {
            $data = array(
                'class' => __CLASS__,
                'method' => $type,
                'message'=> 'Invalid method, this incident will be reported',
                'data' => json_decode(json_encode($xml)),
                'trace' => (string)$e
            );
            return json_encode($data);
            
            # implement logging here, so a log file will be genereated and these kind of events can be converted to methods.
        }
        
    }

    /*
        Parse Call Awnsered Events
    */
    protected function CallAnsweredEvent($xml)
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
        event(new AdvancedCallEvent($CallAnsweredEvent));
        return Null;
    }

    /*
        Parse Call Barged In Events
    */
    protected function CallBargedInEvent($xml)
    {
        $CallBargedInEvent = array(
            "eventType" => (string)"CallBargedInEvent",
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
            "acdUserId" => (string)$xml->eventData->call->acdCallInfo->acdUserId,
            "acdName" => (string)$xml->eventData->call->acdCallInfo->acdName,
            "acdNumber" => (string)$xml->eventData->call->acdCallInfo->acdNumber,
            "numCallsInQueue" => (int)$xml->eventData->call->acdCallInfo->numCallsInQueue,
            "waitTime" => (int)$xml->eventData->call->acdCallInfo->waitTime,
            "callingPartyInfoName" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->name,
            "callingPartyInfoAddress" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->address,
            "callingPartyInfoUserId" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->userId,
            "callingPartyInfoUserDN" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->userDN,
            "callingPartyInfoCallType" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->callType,
            "allowedRecordingControls" => (string)$xml->eventData->call->allowedRecordingControls,
            "callType" => (string)$xml->eventData->call->recordingState
        );
        event(new AdvancedCallEvent($CallBargedInEvent));
        return Null;
    }

    /*
        Parse Call Collecting Events
    */
    protected function CallCollectingEvent($xml)
    {
        $CallCollectingEvent = array(
            "eventType" => (string)"CallCollectingEvent",
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
            "startTime" => (int)$xml->eventData->call->startTime
        );
        event(new AdvancedCallEvent($CallCollectingEvent));
        return Null;
    }
    
    /*
        Parse Call Forwarded Events
    */
    protected function CallForwardedEvent($xml)
    {
        $CallForwardedEvent = array(
            "eventType" => (string)"CallForwardedEvent",
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
            "startTime" => (int)$xml->eventData->call->startTime
        );
        event(new AdvancedCallEvent($CallForwardedEvent));
        return Null;
    }

    /*
        Parse Call Held Events
    */
    protected function CallHeldEvent($xml)
    {
        $CallHeldEvent = array(
            "eventType" => (string)"CallHeldEvent",
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
            "heldTime" => (int)$xml->eventData->call->heldTime,
            "acdUserId" => (string)$xml->eventData->call->acdCallInfo->acdUserId,
            "acdName" => (string)$xml->eventData->call->acdCallInfo->acdName,
            "acdNumber" => (string)$xml->eventData->call->acdCallInfo->acdNumber,
            "numCallsInQueue" => (int)$xml->eventData->call->acdCallInfo->numCallsInQueue,
            "waitTime" => (int)$xml->eventData->call->acdCallInfo->waitTime,
            "callingPartyInfoName" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->name,
            "callingPartyInfoAddress" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->address,
            "callingPartyInfoCallType" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->callType,
            "allowedRecordingControls" => (string)$xml->eventData->call->allowedRecordingControls,
            "recordingState" => (string)$xml->eventData->call->recordingState
        );
        event(new AdvancedCallEvent($CallHeldEvent));
        return Null;
    }

    /*
        Parse Call Originated Events
    */
    protected function CallOriginatedEvent($xml)
    {
        $CallOriginatedEvent = array(
            "eventType" => (string)"CallOriginatedEvent",
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
            "startTime" => (int)$xml->eventData->call->startTime
        );
        event(new AdvancedCallEvent($CallOriginatedEvent));
        return Null;
    }

    /*
        Parse Call Originating Events
    */
    protected function CallOriginatingEvent($xml)
    {
        $CallOriginatingEvent = array(
            "eventType" => (string)"CallOriginatingEvent",
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
            "startTime" => (int)$xml->eventData->call->startTime
        );
        event(new AdvancedCallEvent($CallOriginatingEvent));
        return Null;
    }

    /*
        Parse Call Picked Up Events
    */
    protected function CallPickedUpEvent($xml)
    {
        $CallPickedUpEvent = array(
            "eventType" => (string)"CallPickedUpEvent",
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
        event(new AdvancedCallEvent($CallPickedUpEvent));
        return Null;
    }

    /*
        Parse Call Received Events
    */
    protected function CallReceivedEvent($xml)
    {
        $redirection = array();
        $redirections = $xml->eventData->call->redirections->redirection;

        foreach($redirections as $red)
        {
            $name = (string)$red->party->name;
            $address = (string)$red->party->address;
            $userId = (string)$red->party->userId;
            $callType = (string)$red->party->callType;
            $reason = (string)$red->reason;
            array_push($redirection, array(
                "name" => $name,
                "address" => $address,
                "userId" => $userId,
                "callType" => $callType,
                "reason" => $reason
            ));
        }

        $CallReceivedEvent = array(
            "eventType" => (string)"CallReceivedEvent",
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
            "redirections" => $redirection,
            "startTime" => (int)$xml->eventData->call->startTime,
            "acdUserId" => (string)$xml->eventData->call->acdCallInfo->acdUserId,
            "acdName" => (string)$xml->eventData->call->acdCallInfo->acdName,
            "acdNumber" => (string)$xml->eventData->call->acdCallInfo->acdNumber,
            "numCallsInQueue" => (int)$xml->eventData->call->acdCallInfo->numCallsInQueue,
            "waitTime" => (int)$xml->eventData->call->acdCallInfo->waitTime,
            "callingPartyInfoName" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->name,
            "callingPartyInfoAddress" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->address,
            "callingPartyInfoCallType" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->callType
        );
        event(new AdvancedCallEvent($CallReceivedEvent));
        return Null;
    }

    /*
        Parse Call Recording Started Events
    */
    protected function CallRecordingStartedEvent($xml)
    {
        $CallRecordingStartedEvent = array(
            "eventType" => (string)"CallRecordingStartedEvent",
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
            "startTime" => (int)$xml->eventData->call->startTime,
            "answerTime" => (int)$xml->eventData->call->answerTime,
            "acdUserId" => (string)$xml->eventData->call->acdCallInfo->acdUserId,
            "acdName" => (string)$xml->eventData->call->acdCallInfo->acdName,
            "acdNumber" => (string)$xml->eventData->call->acdCallInfo->acdNumber,
            "numCallsInQueue" => (int)$xml->eventData->call->acdCallInfo->numCallsInQueue,
            "waitTime" => (int)$xml->eventData->call->acdCallInfo->waitTime,
            "callingPartyInfoName" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->name,
            "callingPartyInfoAddress" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->address,
            "callingPartyInfoCallType" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->callType,
            "allowedRecordingControls" => (string)$xml->eventData->call->allowedRecordingControls,
            "recordingState" => (string)$xml->eventData->call->recordingState
        );
        event(new AdvancedCallEvent($CallRecordingStartedEvent));
        return Null;
    }

    /*
        Parse Call Recording Stopped Events
    */
    protected function CallRecordingStoppedEvent($xml)
    {
        $CallRecordingStoppedEvent = array(
            "eventType" => (string)"CallRecordingStoppedEvent",
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
            "appearance" => (int)$xml->eventData->call->appearance,
            "startTime" => (int)$xml->eventData->call->startTime,
            "answerTime" => (int)$xml->eventData->call->answerTime,
            "acdUserId" => (string)$xml->eventData->call->acdCallInfo->acdUserId,
            "acdName" => (string)$xml->eventData->call->acdCallInfo->acdName,
            "acdNumber" => (string)$xml->eventData->call->acdCallInfo->acdNumber,
            "numCallsInQueue" => (int)$xml->eventData->call->acdCallInfo->numCallsInQueue,
            "waitTime" => (int)$xml->eventData->call->acdCallInfo->waitTime,
            "allowedRecordingControls" => (string)$xml->eventData->call->allowedRecordingControls,
            "recordingState" => (string)$xml->eventData->call->recordingState,
            "reason" => (string)$xml->eventData->reason
        );
        event(new AdvancedCallEvent($CallRecordingStoppedEvent));
        return Null;
    }

    /*
        Parse Call Redirected Events
    */
    protected function CallRedirectedEvent($xml)
    {
        $calllist = array();
        $calls = $xml->eventData->calls->call;

        foreach($calls as $call)
        {
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

            array_push($calllist, array(
                "callId" =>$callId,
                "extTrackingId" => $extTrackingId,
                "personality" => $personality,
                "state" => $state,
                "remotePartyName" => $remotePartyName,
                "remotePartyAddress" => $remotePartyAddress,
                "remotePartyUserId" => $remotePartyUserId,
                "remotePartyUserDN" => $remotePartyUserDN,
                "remotePartyCallType" => $remotePartyCallType,
                "redirectAddress" => $redirectAddress,
                "redirectReason" => $redirectReason,
                "redirectTime" => $redirectTime,
                "startTime" => $startTime,
                "answerTime" => $answerTime,
                "totalHeldTime" => $totalHeldTime,
                "detachedTime" => $detachedTime,
                "allowedRecordingControls" => $allowedRecordingControls
            ));
        }
        $CallRedirectedEvent = array(
            "eventType" => (string)"CallRedirectedEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "calls" => $calllist
        );
        event(new AdvancedCallEvent($CallRedirectedEvent));
        return Null;
    }

    /*
        Parse Call Released Events
    */
    protected function CallReleasedEvent($xml)
    {
        $CallReleasedEvent = array(
            "eventType" => (string)"CallReleasedEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "callId" => (string)$xml->eventData->call->callId,
            "extTrackingId" => (string)$xml->eventData->call->extTrackingId,
            "personality" => (string)$xml->eventData->call->personality,
            "state" => (string)$xml->eventData->call->state,
            "releasingParty" => (string)$xml->eventData->call->releasingParty,
            "remotePartyName" => (string)$xml->eventData->call->remoteParty->name,
            "remotePartyAddress" => (string)$xml->eventData->call->remoteParty->address,
            "remotePartyUserId" => (string)$xml->eventData->call->remoteParty->userId,
            "remotePartyUserDN" => (string)$xml->eventData->call->remoteParty->userDN,
            "remotePartycallType" => (string)$xml->eventData->call->remoteParty->callType,
            "startTime" => (int)$xml->eventData->call->startTime,
            "answerTime" => (int)$xml->eventData->call->answerTime,
            "releaseTime" => (int)$xml->eventData->call->releaseTime,
            "allowedRecordingControls" => (string)$xml->eventData->call->allowedRecordingControls
        );
        event(new AdvancedCallEvent($CallReleasedEvent));
        return Null;
    }

    /*
        Parse Call Releasing Events
    */
    protected function CallReleasingEvent($xml)
    {
        $CallReleasingEvent = array(
            "eventType" => (string)"CallReleasingEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "callId" => (string)$xml->eventData->call->callId,
            "extTrackingId" => (string)$xml->eventData->call->extTrackingId,
            "personality" => (string)$xml->eventData->call->personality,
            "state" => (string)$xml->eventData->call->state,
            "releaseCauseInternalReleaseCause" => (string)$xml->eventData->call->releaseCause->internalReleaseCause,
            "releaseCauseCdrTerminationCause" => (int)$xml->eventData->call->releaseCause->cdrTerminationCause,
            "releasingParty" => (string)$xml->eventData->call->releasingParty,
            "remotePartyName" => (string)$xml->eventData->call->remoteParty->name,
            "remotePartyAddress" => (string)$xml->eventData->call->remoteParty->address,
            "remotePartyUserId" => (string)$xml->eventData->call->remoteParty->userId,
            "remotePartyUserDN" => (string)$xml->eventData->call->remoteParty->userDN,
            "remotePartycallType" => (string)$xml->eventData->call->remoteParty->callType,
            "addressOfRecord" => (string)$xml->eventData->call->endpoint->addressOfRecord,
            "appearance" => (int)$xml->eventData->call->appearance,
            "startTime" => (int)$xml->eventData->call->startTime
        );
        event(new AdvancedCallEvent($CallReleasingEvent));
        return Null;
    }

    /*
        Parse Call Retrieved Events
    */
    protected function CallRetrievedEvent($xml)
    {
        $CallRetrievedEvent = array(
            "eventType" => (string)"CallRetrievedEvent",
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
            "remotePartycallType" => (string)$xml->eventData->call->remoteParty->callType,
            "addressOfRecord" => (string)$xml->eventData->call->endpoint->addressOfRecord,
            "appearance" => (int)$xml->eventData->call->appearance,
            "startTime" => (int)$xml->eventData->call->startTime,
            "answerTime" => (int)$xml->eventData->call->answerTime,
            "totalHeldTime" => (int)$xml->eventData->call->totalHeldTime,
            "acdUserId" => (string)$xml->eventData->call->acdCallInfo->acdUserId,
            "acdName" => (string)$xml->eventData->call->acdCallInfo->acdName,
            "acdNumber" => (string)$xml->eventData->call->acdCallInfo->acdNumber,
            "numCallsInQueue" => (int)$xml->eventData->call->acdCallInfo->numCallsInQueue,
            "waitTime" => (int)$xml->eventData->call->acdCallInfo->waitTime,
            "callingPartyInfoName" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->name,
            "callingPartyInfoAddress" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->address,
            "callingPartyInfoCallType" => (string)$xml->eventData->call->acdCallInfo->callingPartyInfo->callType,
            "allowedRecordingControls" => (string)$xml->eventData->call->allowedRecordingControls,
            "recordingState" => (string)$xml->eventData->call->recordingState
        );
        event(new AdvancedCallEvent($CallRetrievedEvent));
        return Null;
    }

    /*
        Parse Call Subscription Events
    */
    protected function CallSubscriptionEvent($xml)
    {
        $CallSubscriptionEvent = array(
            "eventType" => (string)"CallSubscriptionEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "hookStatus" => (string)$xml->eventData->hookStatus
        );
        event(new AdvancedCallEvent($CallSubscriptionEvent));
        return Null;
    }

    /*
        Parse Call Transferred Events
    */
    protected function CallTransferredEvent($xml)
    {
        $CallTransferredEvent = array(
            "eventType" => (string)"CallTransferredEvent",
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
            "remotePartycallType" => (string)$xml->eventData->call->remoteParty->callType,
            "address" => (string)$xml->eventData->call->endpoint->address,
            "appearance" => (int)$xml->eventData->call->appearance,
            "startTime" => (int)$xml->eventData->call->startTime,
            "answerTime" => (int)$xml->eventData->call->answerTime
        );
        event(new AdvancedCallEvent($CallTransferredEvent));
        return Null;
    }

    /*
        Parse Call Updated Events
    */
    protected function CallUpdatedEvent($xml)
    {
        $CallUpdatedEvent = array(
            "eventType" => (string)"CallUpdatedEvent",
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
            "remotePartycallType" => (string)$xml->eventData->call->remoteParty->callType,
            "addressOfRecord" => (string)$xml->eventData->call->endpoint->addressOfRecord,
            "appearance" => (int)$xml->eventData->call->appearance,
            "startTime" => (int)$xml->eventData->call->startTime
        );
        event(new AdvancedCallEvent($CallUpdatedEvent));
        return Null;
    }

    /*
        Parse Conference Held Events
    */
    protected function ConferenceHeldEvent($xml)
    {
        $ConferenceHeldEvent = array(
            "eventType" => (string)"ConferenceHeldEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "state" => (string)$xml->eventData->conference->state,
            "addressOfRecord" => (string)$xml->eventData->conference->endpoint->addressOfRecord,
            "appearance" => (int)$xml->eventData->conference->appearance
        );
        event(new AdvancedCallEvent($ConferenceHeldEvent));
        return Null;
    }

    /*
        Parse Conference Released Events
    */
    protected function ConferenceReleasedEvent($xml)
    {
        $ConferenceReleasedEvent = array(
            "eventType" => (string)"ConferenceReleasedEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "state" => (string)$xml->eventData->conference->state
        );
        event(new AdvancedCallEvent($ConferenceReleasedEvent));
        return Null;
    }

    /*
        Parse Conference Retrieved Events
    */
    protected function ConferenceRetrievedEvent($xml)
    {
        $conferenceParticipantList= array();
        $confParticipantList = $xml->eventData->conference->conferenceParticipantList->conferenceParticipant;

        foreach($confParticipantList as $participant)
        {
            $callId = (string)$participant->callId;
            array_push($conferenceParticipantList, array( "callId"=> $callId));
        }

        $ConferenceRetrievedEvent = array(
            "eventType" => (string)"ConferenceRetrievedEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "state" => (string)$xml->eventData->conference->state,
            "addressOfRecord" => (string)$xml->eventData->conference->endpoint->addressOfRecord,
            "appearance" => (int)$xml->eventData->conference->appearance,
            "conferenceParticipantList" => $conferenceParticipantList
        );
        event(new AdvancedCallEvent($ConferenceRetrievedEvent));
        return Null;
    }

    /*
        Parse Conference Started Events
    */
    protected function ConferenceStartedEvent($xml)
    {
        $conferenceParticipantList= array();
        $confParticipantList = $xml->eventData->conference->conferenceParticipantList->conferenceParticipant;

        foreach($confParticipantList as $participant)
        {
            $callId = (string)$participant->callId;
            array_push($conferenceParticipantList, array( "callId"=> $callId));
        }

        $ConferenceStartedEvent = array(
            "eventType" => (string)"ConferenceStartedEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "state" => (string)$xml->eventData->conference->state,
            "addressOfRecord" => (string)$xml->eventData->conference->endpoint->addressOfRecord,
            "appearance" => (int)$xml->eventData->conference->appearance,
            "conferenceType" => (string)$xml->eventData->conference->conferenceType,
            "conferenceParticipantList" => $conferenceParticipantList
        );
        event(new AdvancedCallEvent($ConferenceStartedEvent));
        return Null;
    }

    /*
        Parse Conference Updates Events
    */
    protected function ConferenceUpdatedEvent($xml)
    {
        $conferenceParticipantList= array();
        $confParticipantList = $xml->eventData->conference->conferenceParticipantList->conferenceParticipant;

        foreach($confParticipantList as $participant)
        {
            $callId = (string)$participant->callId;
            array_push($conferenceParticipantList, array( "callId"=> $callId));
        }

        $ConferenceUpdatedEvent = array(
            "eventType" => (string)"ConferenceUpdatedEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "state" => (string)$xml->eventData->conference->state,
            "addressOfRecord" => (string)$xml->eventData->conference->endpoint->addressOfRecord,
            "appearance" => (int)$xml->eventData->conference->appearance,
            "conferenceParticipantList" => $conferenceParticipantList
        );
        event(new AdvancedCallEvent($ConferenceUpdatedEvent));
        return Null;
    }

    /*
        Parse Hook Status Events
    */
    protected function HookStatusEvent($xml)
    {
        $HookStatusEvent = array(
            "eventType" => (string)"HookStatusEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "state" => (string)$xml->eventData->hookStatus
        );
        event(new AdvancedCallEvent($HookStatusEvent));
        return Null;
    }

    /*
        Parse Subscription Terminated Events
    */
    protected function SubscriptionTerminatedEvent($xml)
    {
        $SubscriptionTerminatedEvent = array(
            "eventType" => (string)"SubscriptionTerminatedEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "httpContactUri" => (string)$xml->httpContact->uri
        );
        event(new AdvancedCallEvent($SubscriptionTerminatedEvent));
        return Null;
    }
}
