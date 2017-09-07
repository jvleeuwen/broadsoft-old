<?php

namespace jvleeuwen\broadsoft\Repositories;

use jvleeuwen\broadsoft\Repositories\Contracts\BsUserInterface;
use jvleeuwen\broadsoft\Database\Models\bsUser;
use jvleeuwen\broadsoft\Database\Models\bsUserAssignedCallcenter;

class BsUserRepository implements BsUserInterface
{
    public function UserdbCompare($bsArray)
    {
        $dbUsers = bsUser::all();
        foreach($dbUsers as $dbUser)
        {
            if(!in_array($dbUser->userId, array_flatten($bsArray)))
            {
                bsUser::where('userId', $dbUser->userId)->delete();
            }
        }
        return True;
    }

    public function SaveToDB($UserArray)
    {
        $new = 0;
        $updates = 0;
        $errors = 0;

        foreach($UserArray as $user)
        {
            $userId = $user['userId']['$']; 
            $firstName = $user['firstName']['$'];
            $lastName = $user['lastName']['$'];
            $groupId = $user['groupId']['$'];
            if(isset($user['number'])){$number = $user['number']['$'];}else{$number = NULL;}
            if(isset($user['extension'])){$extension = $user['extension']['$'];}else{$extension = NULL;}
            // $extension = $user['extension']['$'];
            // Below items may not be set !
            if(isset($user['additionalDetails']))
            {
                if(isset($user['additionalDetails']['mobile'])){$mobile = $user['additionalDetails']['mobile']['$'];}else{$mobile = NULL;}
                if(isset($user['additionalDetails']['emailAddress'])){$emailAddress = $user['additionalDetails']['emailAddress']['$'];}else{$emailAddress = NULL;}
                if(isset($user['additionalDetails']['department'])){$department = $user['additionalDetails']['department']['$'];}else{$department = NULL;}
            }
            else
            {
                $mobile = NULL;
                $emailAddress = NULL;
                $department = NULL;
            }
            
            
            $ExistingUser = bsUser::where('userId', $userId)->first(); // check if userId exists in DB
            if(!$ExistingUser) // if user does not exist, create one
            {
                $NewUser = new bsUser;
                $NewUser->userId = $userId;
                $NewUser->firstName = $firstName;
                $NewUser->lastName = $lastName;
                $NewUser->groupId = $groupId;
                $NewUser->number = $number;
                $NewUser->extension = $extension;
                $NewUser->mobile = $mobile;
                $NewUser->emailAddress = $emailAddress;
                $NewUser->department = $department;
                if($NewUser->save())
                {
                    $new +=1;
                }
                else
                {
                    $errors +=1;
                }
            }
            else //update an existing user
            {
                $ExistingUser->firstName = $firstName;
                $ExistingUser->lastName = $lastName;
                $ExistingUser->groupId = $groupId;
                $ExistingUser->number = $number;
                $ExistingUser->extension = $extension;
                $ExistingUser->mobile = $mobile;
                $ExistingUser->emailAddress = $emailAddress;
                $ExistingUser->department = $department;
                if($ExistingUser->save())
                {
                    $updates +=1;
                }
                else
                {
                    $errors +=1;
                }
            }
        }
        return array('errors' => $errors, 'updates' => $updates, 'new' => $new);
    }

    public function GetAllUsers()
    {
        return bsUser::all();
    }

    public function SaveUserCallCenterServices($CallCenterServicesArray)
    {
        foreach($CallCenterServicesArray as $ServiceUser)
        {
            $ExistingUser = bsUserAssignedCallcenter::where('userId',$ServiceUser['userId'])->where('serviceUserId', $ServiceUser['serviceUserId'])->first();
            if($ExistingUser) // userid/serviceUserID combination exists in the database
            {
                $ExistingUser->userId = $ServiceUser['userId'];
                $ExistingUser->serviceUserId = $ServiceUser['serviceUserId'];
                $ExistingUser->available = $ServiceUser['available'];
                $ExistingUser->skillLevel = $ServiceUser['skillLevel'];
                $ExistingUser->extension = $ServiceUser['extension'];
                $ExistingUser->phoneNumber = $ServiceUser['phoneNumber'];
                $ExistingUser->save();
            }
            else
            {
                $NewUser = New bsUserAssignedCallcenter;
                $NewUser->userId = $ServiceUser['userId'];
                $NewUser->serviceUserId = $ServiceUser['serviceUserId'];
                $NewUser->available = $ServiceUser['available'];
                $NewUser->skillLevel = $ServiceUser['skillLevel'];
                $NewUser->extension = $ServiceUser['extension'];
                $NewUser->phoneNumber = $ServiceUser['phoneNumber'];
                $NewUser->save();
            }
        }
        return True;
    }

    public function CallCenterServicesBsCompare($bsArray)
    {
        return True;
    }

    public function SetAcdState($userId, $acdState)
    {
        $user = bsUser::where('userId', $userId)->first();
        $user->acdState = $acdState;
        return $user->save();
    }
}