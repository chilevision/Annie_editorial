<?php

namespace App\Http\Controllers;

use App\Http\Livewire\Rundown;
use Illuminate\Http\Request;
use App\Models\Rundowns;
use App\Models\Rundown_rows;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

//date_default_timezone_set("Europe/Stockholm");

class Rundowns_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	return view('rundown.index');
    }

    /**
     *    .--.      .-'.      .--.      .--.      .--.      .--.      .`-.      .--.
     *  :::::.\::::::::.\::::::::.\::::::::.\::::::::.\::::::::.\::::::::.\::::::::.\
     * '      `--'      `.-'      `--'      `--'      `--'      `-.'      `--'      `
     * 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_users = User::where('id', '!=', Auth::user()->id)->pluck('name')->toArray();

        return view('rundown.create')->with('all_users', $all_users);
    }

    /**
     *      *    .--.      .-'.      .--.      .--.      .--.      .--.      .`-.      .--.
     *  :::::.\::::::::.\::::::::.\::::::::.\::::::::.\::::::::.\::::::::.\::::::::.\
     * '      `--'      `.-'      `--'      `--'      `--'      `-.'      `--'      `
     * 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_ids = [Auth::user()->id];
        $users = explode (",", $request->input('users'));
        foreach ($users as $user){
            $user_id = User::where('name', $user)->first();
            if ($user_id == null) return redirect('dashboard/rundown/create')->withErrors('User '.$user.' does not exist.')->withInput();
            else{
                array_push($user_ids, $user_id->id);
            }
        }
        $validator = $this->validateTimestamps($request);
        if ($validator){ 
            return redirect('dashboard/rundown/create')->withErrors($validator)->withInput();
        }
        $starttime  = $request->input('start-date') . ' ' . $request->input('start-time');
        $stoptime   = $request->input('stop-date') . ' ' . $request->input('stop-time');
        $duration   = strtotime($stoptime) - strtotime($starttime);

        $newrundown = Rundowns::create([
            'title'         => addslashes($request->input('rundown-title')),
            'owner'         => Auth::user()->id,
            'starttime'		=> $starttime,
            'stoptime'		=> $stoptime,
            'duration'      => $duration,
        ]);
        $newrundown->users()->attach($user_ids);

        return redirect('dashboard/rundown/'.$newrundown->id.'/edit');
    }

    /**
     *    .--.      .-'.      .--.      .--.      .--.      .--.      .`-.      .--.
     *  :::::.\::::::::.\::::::::.\::::::::.\::::::::.\::::::::.\::::::::.\::::::::.\
     * '      `--'      `.-'      `--'      `--'      `--'      `-.'      `--'      `
     * 
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rundown = Rundowns::find($id);
        if ($rundown == null) return redirect(route('rundown.index'))->withErrors(__('rundown.not_exist'));
        
        if ($rundown->users->firstWhere('id', Auth::user()->id) == null) return redirect(route('rundown.index'))->withErrors(__('rundown.permission_denied'));
        $pusher_channel = Settings::where('id', 1)->value('pusher_channel');
        $errors = collect([]);
		return view('rundown.edit')->with([
            'rundown'           => $rundown,
             'errors'           => $errors,
             'pusher_channel'   => $pusher_channel
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Rundowns::findOrFail($id)->delete();
		return redirect('dashboard/rundown');
    }

    /**
     * Show the form for editing the calendar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_calendar($id)
    {
        $rundown = Rundowns::findOrFail($id);
        if (Auth::user()->id == $rundown->owner){
            $startdate  = date('Y-m-d',strtotime($rundown->starttime));
            $stopdate   = date('Y-m-d',strtotime($rundown->stoptime));
            $starttime  = date('H:i',strtotime($rundown->starttime));
            $stoptime   = date('H:i',strtotime($rundown->stoptime));
            $users      = '';
            $all_users = User::where('id', '!=', $rundown->owner)->pluck('name')->toArray();
            foreach ($rundown->users as $user){
                if ($user->id != $rundown->owner){
                    $users = $users . $user->name.',';
                }
            }

            $users = substr($users, 0, -1);
            return view('rundown.edit_calendar')->with([
                'rundown'       => $rundown, 
                'startdate'     => $startdate,
                'stopdate'      => $stopdate,
                'starttime'     => $starttime,
                'stoptime'      => $stoptime,
                'users'         => $users,
                'all_users'     => $all_users
            ]);
        }
        else {
            return redirect(route('rundown.index'))->withErrors(__('rundown.permission_denied'));
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_calendar(Request $request)
    {
        $user_ids = [Auth::user()->id];
        if ($request->input('users') != null){
            $users = explode (",", $request->input('users'));
            foreach ($users as $user){
                $user_id = User::where('name', $user)->first();
                if ($user_id == null) return redirect('dashboard/rundown/create')->withErrors('User '.$user.' does not exist.')->withInput();
                else{
                    array_push($user_ids, $user_id->id);
                }
            }
        }

        $validator = $this->validateTimestamps($request);
        if ($validator){ 
            return back()->withErrors($validator)->withInput();
        }
        $starttime  = $request->input('start-date') . ' ' . $request->input('start-time');
        $stoptime   = $request->input('stop-date') . ' ' . $request->input('stop-time');
        $duration   = strtotime($stoptime) - strtotime($starttime);

        Rundowns::findOrFail($request->input('id'))->update([
            'title'         => $request->input('rundown-title'),
            'starttime'		=> $starttime,
            'stoptime'		=> $stoptime,
            'duration'      => $duration,
        ]);

        Rundowns::findOrFail($request->input('id'))->users()->sync($user_ids);
        if ($request->input('redirect')){
            return redirect($request->input('redirect'));
        }
        return redirect(route('rundown.index'))->with('status', __('rundown.message_date_updated'));
	}

    //Validates timestamps
    private function validateTimestamps($request) {
        $starttime 	= $request->input('start-date') . ' ' . $request->input('start-time');
        $stoptime   = $request->input('stop-date') . ' ' . $request->input('stop-time');
		if (strtotime($starttime)>strtotime($stoptime)) return (__('rundown.message_error_date1'));
		if (strtotime($starttime) == strtotime($stoptime)) return (__('rundown.message_error_date2'));
		
		if (Rundowns::where([
			['id', '!=', $request->input('id')],
			['starttime', '<=', $starttime],
			['stoptime', '>', $starttime],
			])->orWhere([
				['id', '!=', $request->input('id')],
				['starttime', '<', $stoptime],
				['stoptime', '>', $starttime],
			])->exists()) {
			return(__('rundown.message_error_date3'));
   		}
        return;
    }

    public function load($id)
    {
        Rundowns::where('loaded', 1)->update(['loaded' => 0]);
        $rundown = Rundowns::where('id', $id)->first();
        $rundown->loaded = 1;
        $rundown->save();
        return redirect(route('rundown.index'))->with('status', 'Rundown: <i>' . $rundown->title . '</i> ' . __('rundown.isLoaded'));
    }

    public function old_api()
    {
        $loaded_id = Rundowns::where('loaded', 1)->first()->id;
        $rows = Rundown_rows::where('rundown_id', $loaded_id)->get();
        $sorted = sort_rows($rows)[0];
        $filtered = $sorted->where('type', 'VB');
        $output = [];
        if (is_object($filtered) && !empty($filtered)){
            foreach ($filtered as $file){
                array_push($output, [
                    'file'      => $file->source,
                    'frames'    => $file->duration * $file->file_fps,
                    'fps'       => $file->file_fps,
                    'autoplay'  => $file->autotrigg
                ]);
            }
        }
        echo(json_encode($output));
    }
}
