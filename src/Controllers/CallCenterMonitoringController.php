<?php

namespace jvleeuwen\broadsoft\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use jvleeuwen\broadsoft\Events\CallCenterMonitoringEvent;
use jvleeuwen\broadsoft\Controllers\EmailController;
use jvleeuwen\broadsoft\Repositories\Contracts\BsCallCenterMonitoringInterface;

class CallCenterMonitoringController extends Controller
{

    public function __construct(EmailController $email, BsCallCenterMonitoringInterface $bsCallcenterMonitoring)
    {
        $this->email = $email;
        $this->bsCallcenterMonitoring = $bsCallcenterMonitoring;
    }

    /*
        Handle incomming XML messages for the Call Center Agent
    */
    public function Incomming(Request $request)
    {
    	$req = $request->getContent();
        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        return $this->GetEventType($xml, $req);
    }

    /*
        Get the event type from xml data
    */
    protected function GetEventType($xml, $req)
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
            $this->email->sendDebug( __CLASS__, $type, json_encode($xml), (string)$e, $req);
            // return json_encode($data);
            return Null;
        }
    }

    /*
        Parse CallCenter Monitoring Events
    */
    protected function CallCenterMonitoringEvent($xml)
    {
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
            "numStaffedAgentsUnavailable" => (int)$xml->eventData->monitoringStatus->numStaffedAgentsUnavailable
        );
        $this->bsCallcenterMonitoring->SaveToDB($CallCenterMonitoringEvent);
        event(new CallCenterMonitoringEvent($CallCenterMonitoringEvent));
        return Null;
    }

    /*
        Parse CallCenter Monitoring Events
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
        event(new CallCenterMonitoringEvent($SubscriptionTerminatedEvent));
        return Null;
    }
}
