<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use App\Http\Livewire\Rundown;
use Illuminate\Http\Request;
use App\Models\Rundowns;
use Carbon\Carbon;

class Dashboard_controller extends Controller
{
    public function index(){
	    $rundowns 	= Rundowns::count();
	    $nextRun	= Rundowns::where('starttime', '>', time())->orderBy('starttime', 'desc')->pluck('starttime')->first();
		if (!$nextRun) $nextRun = __('dashboard.no_runs'); 

	    return view('dashboard.dashboard', compact('rundowns','nextRun'));
    }

	public function getCalendarData()
	{
		$calendar = [];
		$rundowns = Rundowns::all();
		foreach ($rundowns as $rundown){
			array_push($calendar, [
				'startDate' => Carbon::createFromFormat('Y-m-d H:i:s', $rundown->starttime),
      			'endDate'	=> Carbon::createFromFormat('Y-m-d H:i:s', $rundown->stoptime),
      			'summary'	=> $rundown->title
			]);
		}
		echo json_encode($calendar);
	}

	public function setlang(Request $request)
	{
		if (!in_array($request->input('locale'), ['en', 'sv'])) {        
			abort(404);
		}
	
		App::setLocale($request->input('locale'));
		// Session
		session()->put('locale', $request->input('locale'));
	
		return redirect()->back();
	}
}
