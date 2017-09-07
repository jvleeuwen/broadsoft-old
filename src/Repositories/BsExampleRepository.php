<?php

namespace jvleeuwen\broadsoft\Repositories;

use jvleeuwen\broadsoft\Repositories\Contracts\BsExampleInterface;
use jvleeuwen\broadsoft\Database\Models\bsCallcenter;
use jvleeuwen\broadsoft\Database\Models\bsUserAssignedCallcenter;
use jvleeuwen\broadsoft\Database\Models\bsUser;
use jvleeuwen\broadsoft\Database\Models\bsCallcenterMonitoring;

class BsExampleRepository implements BsExampleInterface
{
    public function GetCallCentersBySlug($slug)
    {
        return bsCallcenter::where('slug', $slug)->get();
    }

    public function GetCallCenterMonitoring()
    {
        return bsCallcenterMonitoring::all();
    }

    public function GetUsersBySlug($slug)
    {
        // $callcenters = $this->GetCallCentersBySlug($slug);
        // $userArray= array();
        // foreach($callcenters as $callcenter)
        // {
        //     $users = bsUserAssignedCallcenter::with('bsUser')->where('serviceUserID', $callcenter->userId)->get();
        //     array_push($userArray, $users);
        // }
        // return $userArray[0];


        return bsUser::whereIn('userId', function($query) use($slug){
			$query->select('userId')
                ->from('bs_user_assigned_callcenters')
                ->whereIn('serviceUserId', function($query) use($slug){
                    $query->select('userId')
                        ->from('bs_callcenters')
                        ->where('slug', $slug);
                });
			})->get();
        



        // $test =  bsUser::whereIn('userId', function($query) use($slug){
		// 	$query->select('userId')
        //         ->from('bs_user_assigned_callcenters')
        //         ->whereIn('sad', function($query) use($slug){
        //             $query->select('userId')
        //                 ->from('bs_callcenters')
        //                 ->where('slug', "'$slug'");
        //         });
		// 	})->get();
        // dd($test);
    }
}