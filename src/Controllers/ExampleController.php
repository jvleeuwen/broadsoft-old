<?php

namespace jvleeuwen\broadsoft\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use jvleeuwen\broadsoft\Repositories\Contracts\BsExampleInterface;


class ExampleController extends Controller
{
    public function __construct(BsExampleInterface $bsExample)
    {
        $this->bsExample = $bsExample;
    }

    public function Index()
    {
        $data = array(
            'routes' => Route::getRoutes()->get()
        );
        return view('broadsoft::bs.example.index', $data);
    }

    public function Agents($slug)
    {
        $data = array(
            'slug' => (string)$slug,
            'callcenters' => $this->bsExample->GetCallCentersBySlug($slug),
            'users' => $this->bsExample->GetUsersBySlug($slug),
            'routes' => Route::getRoutes()->get()
        );
        return view('broadsoft::bs.example.agents', $data);
    }

    public function CallCenterMonitoring()
    {
        $data = array(
            'callcenters' => $this->bsExample->GetCallCenterMonitoring(),
            'routes' => Route::getRoutes()->get()
        );
        return view('broadsoft::bs.example.callcentermonitoring', $data);
    }
}