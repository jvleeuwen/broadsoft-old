<?php

namespace jvleeuwen\broadsoft\Repositories;

use jvleeuwen\broadsoft\Repositories\Contracts\BsCallCenterInterface;
use jvleeuwen\broadsoft\Database\Models\bsCallcenter;

class BsCallCenterRepository implements BsCallCenterInterface
{
    public function SaveToDB($CallCenterArray)
    {
        $new = 0;
        $updates = 0;
        $errors = 0;

        foreach($CallCenterArray as $CallCenter)
        {
            // return true;
            $userId = $CallCenter['userId']['$']; 
            $firstName = $CallCenter['firstName']['$'];
            $lastName = $CallCenter['lastName']['$'];
            $groupId = $CallCenter['groupId']['$'];
            if(isset($CallCenter['extension']))
            {
                $extension = $CallCenter['extension']['$'];
            }
            else
            {
                $extension = NULL;
            }
            // Below items may not be set !
            if(isset($CallCenter['additionalDetails']))
            {
                if(isset($CallCenter['additionalDetails']['department'])){$department = $CallCenter['additionalDetails']['department']['$'];}else{$department = NULL;}
            }
            else
            {
                $department = NULL;
            }
            
            
            $ExistingCallCenter = bsCallcenter::where('userId', $userId)->first(); // check if userId exists in DB
            if(!$ExistingCallCenter) // if user does not exist, create one
            {
                $NewCallCenter = new bsCallcenter;
                $NewCallCenter->userId = $userId;
                $NewCallCenter->firstName = $firstName;
                $NewCallCenter->lastName = $lastName;
                $NewCallCenter->groupId = $groupId;
                $NewCallCenter->extension = $extension;

                if($NewCallCenter->save())
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
                $ExistingCallCenter->firstName = $firstName;
                $ExistingCallCenter->lastName = $lastName;
                $ExistingCallCenter->groupId = $groupId;
                $ExistingCallCenter->extension = $extension;
                if($ExistingCallCenter->save())
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
}