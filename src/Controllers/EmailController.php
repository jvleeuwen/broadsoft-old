<?php

namespace jvleeuwen\broadsoft\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function SendDebug($class, $method, $data, $trace, $req)
    {
        Mail::send('broadsoft::mail.debug', ['class' => $class, 'method'=>$method, 'data'=> $data, 'trace'=> $trace, 'request' => $req], function ($message)
        {
            $message->from('noreply@broadsoftpackage.com', 'noreply@broadsoftpackage.com');
            $message->to(env('BS_CONTACT_MAIL'));
        });
        return True;
    }
}