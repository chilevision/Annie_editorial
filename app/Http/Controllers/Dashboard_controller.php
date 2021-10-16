<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rundowns;

class Dashboard_controller extends Controller
{
    public function index(){
	    $rundowns 	= Rundowns::count();
	    $nextRun	= Rundowns::where('starttime', '>', time())->orderBy('starttime', 'desc')->pluck('starttime')->first();
	    if (!$nextRun) $nextRun = __('dashboard.no_runs'); 
	    else $nextRun = date("Y-m-d H:i",$nextRun);

	    return view('dashboard.dashboard', compact('rundowns','nextRun'));
    }
}
