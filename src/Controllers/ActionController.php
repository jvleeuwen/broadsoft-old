<?php

namespace jvleeuwen\broadsoft\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

use jvleeuwen\broadsoft\Repositories\Contracts\BsUserInterface;
use jvleeuwen\broadsoft\Repositories\Contracts\BsCallCenterInterface;

class ActionController extends Controller
{
    
    public function __construct(BsUserInterface $bsUser, BsCallCenterInterface $bsCallCenter)
    {
        $this->bsUser = $bsUser;
        $this->bsCallcenter = $bsCallCenter;
        $this->default_user = env('BS_QRY_USER').'@'.Str::lower(env('BS_GROUP')).'.'.env('BS_FQDN');
    }

    private function RequestInit()
    {
       $client = new Client([
            'base_uri' => 'https://'. env('BS_XSI_HOST'),// Base URI is used with relative requests
            'timeout'  => 2.0, // You can set any number of default request options.
        ]); 
        return $client;
    }

    private function RequestAction($cmd, $startIndex=1, $user=NULL)
    {
        if(!$user){$user=$this->default_user;}
        if($startIndex > 1){
            $uri = '/'.env('BS_ACTION_PREFIX').'/v2.0/user/'.$user. $cmd .'&start='.$startIndex;
        }
        else{
            $uri = '/'.env('BS_ACTION_PREFIX').'/v2.0/user/'.$user. $cmd;
        }
        return $uri;
    }

    private function RequestEvent($cmd)
    {
        $uri = '/'.env('BS_EVENT_PREFIX').'/v2.0/user/'.env('BS_QRY_USER').'@'.Str::lower(env('BS_GROUP')).'.'.env('BS_FQDN'). $cmd; 
        return $uri; 
    }

    private function RequestResponse($base, $uri)
    {
        $response = $base->request('GET', $uri, ['auth' => [
                env('BS_ADMIN_USER'), 
                env('BS_ADMIN_PASS')
        ]]);
        return $response;
    }

    public function GetUsers()
    {
        $blacklist = explode(',', env('BS_USERID_BLACKLIST'));
        $accepted_domains = explode(',',env('BS_ACCEPTED_DOMAINS'));
        $blacklistFirstNames = array(
            "FAX",
            "Hunt Group",
            "Meet-Me Conferencing",
            "Voice Messaging Group",
            "Auto Attendant"
        );
        $userArray = array();
        $callCenterArray = array();
        $base = $this->RequestInit();
        $uri = $this->RequestAction('/directories/group?format=json'); // Gets all users from the group directory
        $response = $this->RequestResponse($base,$uri);
        $data = json_decode($response->getBody(), true);
        $startIndex = $data['Group']['startIndex']['$'];
        $numberOfRecords = $data['Group']['numberOfRecords']['$'];
        $totalAvailableRecords = $data['Group']['totalAvailableRecords']['$'];
        $parsedRecords = $startIndex+$numberOfRecords;
        $users = $data['Group']['groupDirectory']['directoryDetails'];
        foreach($users as $user)
        {   
            if(!in_array($user['userId']['$'], $blacklist))
            {
                foreach($accepted_domains as $domain)
                {
                    if(str_contains($user['userId']['$'], $domain))
                    {
                        if($user['firstName']['$'] == "Call Center")
                        {
                            array_push($callCenterArray, $user);
                        }
                        else
                        {
                            if(!in_array($user['firstName']['$'], $blacklistFirstNames))
                            {
                                array_push($userArray, $user);
                            }
                        }
                    }
                }   
            }
        }

        while($parsedRecords <= $totalAvailableRecords){ 
            $base = $this->RequestInit();
            $uri = $this->RequestAction('/directories/group?format=json', $parsedRecords);
            $response = $this->RequestResponse($base,$uri);
            $data = json_decode($response->getBody(), true);
            $startIndex = $data['Group']['startIndex']['$'];
            $numberOfRecords = $data['Group']['numberOfRecords']['$'];
            $totalAvailableRecords = $data['Group']['totalAvailableRecords']['$'];
            $users = $data['Group']['groupDirectory']['directoryDetails'];
            foreach($users as $user)
            {   
                if(!in_array($user['userId']['$'], $blacklist))
                {
                    foreach($accepted_domains as $domain)
                    {
                        if(str_contains($user['userId']['$'], $domain))
                        {
                            if($user['firstName']['$'] == "Call Center")
                            {
                                if(isset($user['extension']))
                                {
                                    array_push($callCenterArray, $user);
                                }
                            }
                            else
                            {
                                if(isset($user['extension']))
                                {
                                    if(!in_array($user['firstName']['$'], $blacklistFirstNames))
                                    {
                                        array_push($userArray, $user);
                                    }
                                }
                            }
                        }
                    }   
                }
            }
            $parsedRecords +=$numberOfRecords;
        }
        $this->bsUser->SaveToDB($userArray); // inserts users to the database
        $this->bsCallcenter->SaveToDB($callCenterArray); //inserts callcenters into the database.
        $this->bsUser->UserdbCompare($userArray); //compares users in the db vs from broadsoft, and deletes the ones missing
        // return response()->json($userArray); // returns json response with user accounts
        return "I have proccessed all user accounts";
    }

    public function GetUserCallCenterServices()
    {
        $users = $this->bsUser->GetAllUsers();
        $bs_user_assigned_callcenters = array();
        foreach($users as $user)
        {
            try
            {
                $base = $this->RequestInit();
                $uri = $this->RequestAction('/services/callcenter?format=json', 0, $user->userId); // Gets all users from the group directory
                $response = $this->RequestResponse($base,$uri);
                $data = json_decode($response->getBody(), true); 

                if(isset($data))
                {
                    $acdState = $data['CallCenter']['agentACDState']['$'];
                    $callcenterList = $data['CallCenter']['callCenterList'];
                    foreach($callcenterList as $callcenterItem)
                    {
                        foreach($callcenterItem as $callcenter)
                        {
                            if(isset($callcenter['serviceUserId']))
                            {
                                $serviceUserId = $callcenter['serviceUserId']['$'];
                                $available = $callcenter['available']['$'];
                                if(isset($callcenter['extension'])){$extension =$callcenter['extension']['$'];}else{$extension=NULL;}
                                if(isset($callcenter['phoneNumber'])){$phoneNumber=$callcenter['phoneNumber']['$'];}else{$phoneNumber=NULL;}
                                if(isset($callcenter['skillLevel'])){$skillLevel=$callcenter['skillLevel']['$'];}else{$skillLevel=NULL;}
                                $userId = $user->userId;

                                array_push($bs_user_assigned_callcenters, array(
                                    'serviceUserId' => $serviceUserId,
                                    'available' => (bool)$available,
                                    'extension' => (int)$extension,
                                    'phoneNumber' => $phoneNumber,
                                    'skillLevel' => (int)$skillLevel,
                                    'userId' => $userId
                                ));
                            }
                        } 
                    }
                    $this->bsUser->SetAcdState($userId, $acdState);
                }
            }
            catch (\GuzzleHttp\Exception\RequestException $e)
            {
                // do not send a response.
            }
        }
        $this->bsUser->SaveUserCallCenterServices($bs_user_assigned_callcenters);
        $this->bsUser->CallCenterServicesBsCompare($bs_user_assigned_callcenters);// moet nog verder uitgewerkt worden.
        // return $bs_user_assigned_callcenters;
        return "I have processed all user services for all available users";
    }
}