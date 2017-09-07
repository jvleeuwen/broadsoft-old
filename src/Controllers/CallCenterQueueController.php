<?php

namespace jvleeuwen\broadsoft\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use jvleeuwen\broadsoft\Events\CallCenterAgentEvent;

class CallCenterQueueController extends Controller
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
        Parse ACD Call Abandoned Events
    */
    protected function ACDCallAbandonedEvent($xml)
    {
        $ACDCallAbandonedEvent = array(
            "eventType" => (string)"ACDCallAbandonedEvent",
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
        );
        event(new CallCenterQueueEvent($ACDCallAbandonedEvent));
        return Null;
    }

    /*
        Parse ACD Call Added Events
    */
    protected function ACDCallAddedEvent($xml)
    {
        $ACDCallAddedEvent = array(
            "eventType" => (string)"ACDCallAddedEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "callId" => (string)$xml->eventData->queueEntry->callId,
            "extTrackingId" => (string)$xml->eventData->queueEntry->extTrackingId,
            "remotePartyName" => (string)$xml->eventData->queueEntry->remoteParty->name,
            "remotePartyAddress" => (string)$xml->eventData->queueEntry->remoteParty->address,
            "addTime" => (int)$xml->eventData->queueEntry->addTime,
            "acdName" => (string)$xml->eventData->queueEntry->acdName,
            "acdNumber" => (string)$xml->eventData->queueEntry->acdNumber,
            "acdPriority" => (string)$xml->eventData->queueEntry->acdPriority,
            "addTimeInPriorityBucket" => (int)$xml->eventData->queueEntry->addTimeInPriorityBucket,
            "position" => (int)$xml->eventData->position
        );
        event(new CallCenterQueueEvent($ACDCallAddedEvent));
        return Null;
    }

    /*
        Parse ACD Call Awnsered By Agent Events
    */
    protected function ACDCallAnsweredByAgentEvent($xml)
    {
        $ACDCallAnsweredByAgentEvent = array(
            "eventType" => (string)"ACDCallAnsweredByAgentEvent",
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
            "positiansweringUserIdon" => (string)$xml->eventData->queueEntry->answeringUserId,
            "answeringCallId" => (string)$xml->eventData->queueEntry->answeringCallId
        );
        event(new CallCenterQueueEvent($ACDCallAnsweredByAgentEvent));
        return Null;
    }

    /*
        Parse ACD Call Bounced Events
    */
    protected function ACDCallBouncedEvent($xml)
    {
        $ACDCallBouncedEvent = array(
            "eventType" => (string)"ACDCallBouncedEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "callId" => (string)$xml->eventData->queueEntry->callId,
            "extTrackingId" => (string)$xml->eventData->queueEntry->extTrackingId,
            "remotePartyName" => (string)$xml->eventData->queueEntry->remoteParty->name,
            "remotePartyAddress" => (string)$xml->eventData->queueEntry->remoteParty->address,
            "addTime" => (int)$xml->eventData->queueEntry->addTime,
            "acdName" => (string)$xml->eventData->queueEntry->acdName,
            "acdNumber" => (string)$xml->eventData->queueEntry->acdNumber,
            "position" => (int)$xml->eventData->position

        );
        event(new CallCenterQueueEvent($ACDCallBouncedEvent));
        return Null;
    }

    /*
        Parse ACD Call Offered To Agent Events
    */
    protected function ACDCallOfferedToAgentEvent($xml)
    {
        $ACDCallOfferedToAgentEvent = array(
            "eventType" => (string)"ACDCallOfferedToAgentEvent",
            "eventID" => (string)$xml->eventID,
            "sequenceNumber" => (int)$xml->sequenceNumber,
            "subscriptionId" => (string)$xml->subscriptionId,
            "targetId" => (string)$xml->targetId,
            "callId" => (string)$xml->eventData->queueEntry->callId,
            "extTrackingId" => (string)$xml->eventData->queueEntry->extTrackingId,
            "remotePartyName" => (string)$xml->eventData->queueEntry->remoteParty->name,
            "remotePartyAddress" => (string)$xml->eventData->queueEntry->remoteParty->address,
            "addTime" => (int)$xml->eventData->queueEntry->addTime,
            "acdName" => (string)$xml->eventData->queueEntry->acdName,
            "acdNumber" => (string)$xml->eventData->queueEntry->acdNumber,
            "acdPriority" => (string)$xml->eventData->queueEntry->acdPriority,
            "addTimeInPriorityBucket" => (int)$xml->eventData->queueEntry->addTimeInPriorityBucket
        );
        event(new CallCenterQueueEvent($ACDCallOfferedToAgentEvent));
        return Null;
    }

    /*
        Parse ACD Call Overflowed Events
    */
    protected function ACDCallOverflowedEvent($xml)
    {
        $ACDCallOverflowedEvent = array(
            "eventType" => (string)"ACDCallOverflowedEvent",
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
            "redirectAddress" => (string)$xml->eventData->redirect->address,
            "redirectReason" => (string)$xml->eventData->redirect->reason,
            "redirectTime" => (int)$xml->eventData->redirect->redirectTime
        );
        event(new CallCenterQueueEvent($ACDCallOverflowedEvent));
        return Null;
    }

    /*
        Parse ACD Call Overflowed Treatment Completed Events
    */
    protected function ACDCallOverflowedTreatmentCompletedEvent($xml)
    {
        $ACDCallOverflowedTreatmentCompletedEvent = array(
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
        );
        event(new CallCenterQueueEvent($ACDCallOverflowedTreatmentCompletedEvent));
        return Null;
    }

    /*
        Parse ACD Call Transferred Events
    */
    protected function ACDCallTransferredEvent($xml)
    {
        $ACDCallTransferredEvent = array(
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
        );
        event(new CallCenterQueueEvent($ACDCallTransferredEvent));
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
            "userId" => (string)$xml->userId,
            "subscriptionId" => (string)$xml->subscriptionId,
            "externalApplicationId" => (string)$xml->externalApplicationId,
            "httpContact" => (string)$xml->httpContact->uri
        );
        event(new CallCenterQueueEvent($SubscriptionTerminatedEvent));
        return Null;
    }
}
