<?php

namespace App\Http\Controllers;

use App\Http\Livewire\Rundown;
use Illuminate\Http\Request;
use App\Models\Rundowns;
use App\Models\Rundown_rows;
use App\Models\Settings;
use App\Models\User;
use App\Events\RundownEvent;
use App\Models\Mediafiles;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Mpdf;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_users = User::where('id', '!=', Auth::user()->id)->pluck('username')->toArray();

        return view('rundown.create')->with('all_users', $all_users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['rundown-title' => 'required|max:50']);
        $user_ids = [Auth::user()->id];
        $users = $request->input('users');
        if($users){
            $users = explode (",", $request->input('users'));
            foreach ($users as $user){
                $user_id = User::where('username', $user)->first();
                if ($user_id == null) return back()->withErrors('User '.$user.' does not exist.')->withInput();
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rundown = Rundowns::find($id);
        if ($rundown == null) return redirect(route('rundown.index'))->withErrors(__('rundown.not_exist'));
        if ($rundown->users->firstWhere('id', Auth::user()->id) == null && !Auth::user()->admin) return redirect(route('rundown.index'))->withErrors(__('rundown.permission_denied'));
        
        $pusher_channel = Settings::where('id', 1)->value('pusher_channel');
        $colors = unserialize(Settings::where('id', 1)->value('colors'));
        $errors = collect([]);
		return view('rundown.edit')->with([
            'rundown'           => $rundown,
             'errors'           => $errors,
             'pusher_channel'   => $pusher_channel,
             'colors'           => $colors
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
        if (Auth::user()->id == $rundown->owner || Auth::user()->admin){
            $startdate  = date('Y-m-d',strtotime($rundown->starttime));
            $stopdate   = date('Y-m-d',strtotime($rundown->stoptime));
            $starttime  = date('H:i',strtotime($rundown->starttime));
            $stoptime   = date('H:i',strtotime($rundown->stoptime));
            $users      = '';
            $all_users = User::where('id', '!=', $rundown->owner)->pluck('username')->toArray();
            foreach ($rundown->users as $user){
                if ($user->id != $rundown->owner){
                    $users = $users . $user->username.',';
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
        $owner = Rundowns::where('id', $request->input('id'))->value('owner');
        if ($owner){
            $user_ids = [$owner];
            if ($request->input('users') != null){
                $users = explode (",", $request->input('users'));
                foreach ($users as $user){
                    $user_id = User::where('username', $user)->first();
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
            event(new RundownEvent(['type' => 'render', 'id' => '', 'title' => $request->input('rundown-title')], $request->input('id')));
            if ($request->input('redirect')){
                return redirect($request->input('redirect'));
            }
            return redirect(route('rundown.index'))->with('status', __('rundown.message_date_updated'));
        }
	}

    public function users(Request $request)
    {
        if ($request->users){
            $activeUsers = [];
            $users = json_decode($request->users);
            foreach ($users as $user){
                Cache::has('user-is-online-' . $user) ? $active = 1 : $active = 0;
                array_push($activeUsers, ['user' => $user, 'active' => $active]);
            }
            echo json_encode($activeUsers);
        }
        else{
            return false;
        }
    }

    /**
     * Show the teleprompter for a specific rundow.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_prompter($id = null)
    {
        $pusher_channel     = Settings::where('id', 1)->value('pusher_channel');

        if ($id != null){
            $rundown    = Rundowns::where('id', $id)->first();
            $global     = false;
        }
        else {
            $rundown    = Rundowns::where('loaded', 1)->first();
            $global     = true;
        }
        return view('rundown.prompter')->with([
            'rundown'           => $rundown,
            'pusher_channel'    => $pusher_channel,
            'global'            => $global
        ]);
    }

    /**
     * Renders printable wersion of rundown.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print(Request $request)
    {
        $rundown    = Rundowns::find($request->input('id'));
        $settings   = Settings::where('id', 1)->first();
        if ($rundown == null) return redirect(route('rundown.index'))->withErrors(__('rundown.not_exist'));
        if ($rundown->users->firstWhere('id', Auth::user()->id) == null && !Auth::user()->admin) return redirect(route('rundown.index'))->withErrors(__('rundown.permission_denied'));
        $rows           = Rundown_rows::where('rundown_id', $request->input('id'))->get();
        $rundownrows    = sort_rows($rows)[0];
        $timer          = strtotime($rundown->starttime);
        $filename 	    = 'HDA_Rundown'.sprintf("%06d", $request->input('id')).'.pdf';
        $page_numbers   = [];
        $page           = 'A';
        $page_number    = 1;
        foreach ($rundownrows as $row){
            switch ($row->type){
                case 'PRE':
                    break;
                case 'BREAK':
                    $page++;
                    $page_number = 1;
                    break;
                default:
                    $page_numbers[$row->id] = $page.$page_number;
                    if (!$row->Rundown_meta_rows->isEmpty()){
                        $i = 1;
                        foreach ($row->Rundown_meta_rows as $meta_row ){
                            $page_numbers['m'.$meta_row->id] = $page.$page_number.'-'.$i;
                            $i++;
                        }
                        
                    }
                    $page_number++;
                    break;
            }
        }
        $mpdf           = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '50',
            'margin_bottom' => '25',
            'margin_left'   => '10',
            'margin_right'  => '10',
            'margin_footer' => '5',
            'defaultPageNumStyle' => '1'
        ]);
        $mpdf->showImageErrors = true;
        // Define the Headers before writing anything so they appear on the first page

        $logo = 'https://i.ibb.co/qC4nSnY/annie-h-logo-kopia.jpg';
        if (env('APP_ENV') == 'production'){
            $logo = resource_path() . '/uploads/annie-h-logo.jpg';
            if (get_custom_logo()){
                $logo = resource_path() . '/uploads/' . get_custom_logo();
            }
        }
        
        $mpdf->SetHTMLHeader(view('rundown.print.header')->with(['rundown' => $rundown, 'logo' => $logo]),'O');

        $mpdf->SetHTMLFooter(view('rundown.print.footer')->with([
            'rundown'   => $rundown,
            'settings'  => $settings
        ]));

        $mpdf->WriteHTML(view('rundown.print.print')->with([
            'rundown'       => $rundown,
            'rundownrows'   => $rundownrows,
            'timer'         => $timer,
            'page_numbers'  => $page_numbers,
            'pages'         => $request->input(),
        ]));
        
        $mpdf->Output($filename, 'I');
    }
    /**
     * Creates a XML-file for CasparCG rundown.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generateXML($id)
    {
        $settings   = Settings::where('id', 1)->first();
        $rundown    = Rundowns::find($id);
        if ($rundown == null) return redirect(route('rundown.index'))->withErrors(__('rundown.not_exist'));
        if ($rundown->users->firstWhere('id', Auth::user()->id) == null && !Auth::user()->admin) return redirect(route('rundown.index'))->withErrors(__('rundown.permission_denied'));
        $rows           = Rundown_rows::where('rundown_id', $id)->get();
        $filename 	    = 'HDA_Rundown'.sprintf("%06d", $id).'.xml';
        $rundownrows    = sort_rows($rows)[0];

        $output = View::make('rundown.xml')->with([
            'rundown'           => $rundown,
             'rundownrows'      => $rundownrows,
             'settings'         => $settings,
        ])->render();
        $output = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n" . $output;
        return response($output)
        ->header('Content-Type', 'text/xml')
        ->header('Cache-Control', 'public')
        ->header('Content-Description', 'File Transfer')
        ->header('Content-Disposition', 'attachment; filename=' . $filename . '')
        ->header('Content-Transfer-Encoding', 'binary');
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
        $loaded = Rundowns::where('loaded', 1)->first();
        if ($loaded != null){
            $rows = Rundown_rows::where('rundown_id', $loaded->id)->get();
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
}
