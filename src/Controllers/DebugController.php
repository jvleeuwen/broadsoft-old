<?php

namespace jvleeuwen\broadsoft\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DebugController extends Controller
{
    public function Index()
    {
        $data = array(
            'routes' => Route::getRoutes()->get()
        );
        return view('broadsoft::bs.debug.index', $data);
    }

    public function CallCenterAgentEvent()
    {
        $data = array(
            'routes' => Route::getRoutes()->get()
        );
    	return view('broadsoft::bs.debug.callcenteragent', $data);
    }

    public function CallCenterQueueEvent()
    {
        $data = array(
            'routes' => Route::getRoutes()->get()
        );
        return view('broadsoft::bs.debug.callcenterqueue', $data);
    }

    public function AdvancedCallEvent()
    {
        $data = array(
            'routes' => Route::getRoutes()->get()
        );
        return view('broadsoft::bs.debug.advancedcall', $data);
    }

    public function CallCenterMonitoringEvent()
    {
        $data = array(
            'routes' => Route::getRoutes()->get()
        );
        return view('broadsoft::bs.debug.callcentermonitoring', $data);
    }
}