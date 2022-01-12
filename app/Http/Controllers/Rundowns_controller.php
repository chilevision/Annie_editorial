<?php

namespace App\Http\Controllers;

use App\Http\Livewire\Rundown;
use Illuminate\Http\Request;
use App\Models\Rundowns;
use App\Models\Rundown_rows;
use App\Models\Settings;
use App\Models\User;
use App\Events\RundownEvent;
use Illuminate\Support\Facades\Auth;
use Mpdf;

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
        event(new RundownEvent(['type' => 'render', 'id' => '', 'title' => $request->input('rundown-title')], $request->input('id')));
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

    /**
     * Renders printable wersion of rundown.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $rundown = Rundowns::find($id);
        if ($rundown == null) return redirect(route('rundown.index'))->withErrors(__('rundown.not_exist'));
        if ($rundown->users->firstWhere('id', Auth::user()->id) == null) return redirect(route('rundown.index'))->withErrors(__('rundown.permission_denied'));
        $rows           = Rundown_rows::where('rundown_id', $id)->get();
        $rundownrows    = sort_rows($rows)[0];
        $timer          = strtotime($rundown->starttime);
        $notes          = [];
        $filename 	    = 'HDA_Rundown'.sprintf("%06d", $id).'.pdf';
        $mixer_source   = $rows->where('type', 'MIXER');
        $cams = array_unique(Rundown_rows::where('rundown_id', $id)->where('type', 'MIXER')->orderBy('source', 'asc')->pluck('source')->toArray());
        foreach ($cams as $cam){
            $cam_notes = $mixer_source->where('source', $cam)->all();
            $notes[$cam] = $cam_notes;
        }
        $mpdf = new \Mpdf\Mpdf([
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
        $mpdf->SetHTMLHeader('
        <div style="text-align: right; font-size: 7pt; color: #000000; font-weight: regular; font-family: serif;">
            '.date('Y-m-d').'
        </div>
        <table class="head-table"><tr>
            <td><img src="https://i.ibb.co/qC4nSnY/annie-h-logo-kopia.jpg" style="max-width: 140px; max-height: 80px;"/></td>
            <td><h3>'. $rundown->title .'</h3></td>
        </tr></table>
        <table style="margin-bottom: 30px">
            <tr>
                <td style="text-align: right; font-weight: bold; padding-right: 10px">'.__('rundown.air_date').'</td>
                <td>'.gmdate('Y-m-d', strtotime($rundown->starttime)).'</td>
            </tr>
            <tr>
                <td style="text-align: right; font-weight: bold; padding-right: 10px">'. __('rundown.air_time') .':</td>
                <td>'. date('H:i', strtotime($rundown->starttime)).' - '.date('H:i', strtotime($rundown->stoptime)) .'</td>
            </tr>
            <tr>
                <td style="text-align: right; font-weight: bold; padding-right: 10px">'. __('rundown.lenght') .':</td>
                <td>'. gmdate('H:i', $rundown->duration) .'</td>
            </tr>
        </table>','O');

        $mpdf->SetHTMLFooter('
        <table width="100%" style="vertical-align: bottom; font-family: serif; 
            font-size: 7pt; color: #000000; font-weight: regular;">
            <tr>
                <td width="33%">'.Settings::where('id', 1)->value('name').'</td>
                <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right; font-style: italic;">'.$rundown->title.'</td>
            </tr>
        </table>');  // Note that the second parameter is optional : default = 'O' for ODD

        $mpdf->WriteHTML(view('rundown.print')->with([
            'rundown'       => $rundown,
            'rundownrows'   => $rundownrows,
            'timer'         => $timer,
            'page'          => 'A',
            'page_number'   => 1,
            'notes'         => $notes
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
        $rundown = Rundowns::find($id);
        if ($rundown == null) return redirect(route('rundown.index'))->withErrors(__('rundown.not_exist'));
        if ($rundown->users->firstWhere('id', Auth::user()->id) == null) return redirect(route('rundown.index'))->withErrors(__('rundown.permission_denied'));
        $rows           = Rundown_rows::where('rundown_id', $id)->get();
        $server         = Settings::where('id', 1)->value('templateserver_name');
        $serverchannel  = Settings::where('id', 1)->value('templateserver_channel');
        $filename 	    = 'HDA_Rundown'.sprintf("%06d", $id);
        $rundownrows    = sort_rows($rows)[0];
        $xml            = new \DOMDocument('1.0','UTF-8');

		$xml->formatOutput = true;
		$items = $xml->createElement('items');
		$xml->appendChild($items);
		$triggering = $xml->createElement('allowremotetriggering', 'false');
		$items->appendChild($triggering);
        if (!$rundownrows->isEmpty()){
            foreach ($rundownrows as $row){
                list($r, $g, $b) = sscanf($row->color, "%02x%02x%02x");
                $item 						= $xml->createElement('item');
                $grouptype 					= $xml->createElement('type', 'GROUP');						        $item->appendChild($grouptype);
                $grouplabel 				= $xml->createElement('label', $row->story);		                $item->appendChild($grouplabel);
                $expanded 					= $xml->createElement('expanded', 'false');					        $item->appendChild($expanded);
                $groupchannel				= $xml->createElement('channel', 1);						        $item->appendChild($groupchannel);
                $groupvideolayer			= $xml->createElement('videolayer', 10);					        $item->appendChild($groupvideolayer);
                $groupdelay					= $xml->createElement('delay', 0);							        $item->appendChild($groupdelay);
                $groupduration				= $xml->createElement('duration', 0);						        $item->appendChild($groupduration);
                $groupallowgpi				= $xml->createElement('allowgpi', 'false');					        $item->appendChild($groupallowgpi);
                $groupallowremotetriggering	= $xml->createElement('allowremotetriggering', 'false');	        $item->appendChild($groupallowremotetriggering);
                $groupremotetriggerid		= $xml->createElement('remotetriggerid');					        $item->appendChild($groupremotetriggerid);
                $groupstoryid				= $xml->createElement('storyid');							        $item->appendChild($groupstoryid);
                $groupnotes					= $xml->createElement('notes');								        $item->appendChild($groupnotes);
                $groupautostep				= $xml->createElement('autostep', 'false');					        $item->appendChild($groupautostep);
                $groupautoplay				= $xml->createElement('autoplay', 'false');					        $item->appendChild($groupautoplay);
                $groupcolor					= $xml->createElement('color', 'rgba('.$r.','.$g.','.$b.',128)');	$item->appendChild($groupcolor);
                $groupitems					= $xml->createElement('items');								        $item->appendChild($groupitems);

                $items->appendChild($item);

                if (!$row->Rundown_meta_rows->isEmpty()){
                    foreach ($row->Rundown_meta_rows as $meta_row ){
                        if ($meta_row->type == 'GFX'){
                            $groupitem = $xml->createElement('item');
							$groupitems->appendChild($groupitem);
                            $type 					= $xml->createElement('type', 'TEMPLATE');					    $groupitem->appendChild($type);
                            $devicename 			= $xml->createElement('devicename', $server);	                $groupitem->appendChild($devicename);
                            $label 					= $xml->createElement('label', $meta_row->title);			    $groupitem->appendChild($label);
                            $name 					= $xml->createElement('name', $meta_row->source);			    $groupitem->appendChild($name);
                            $channel				= $xml->createElement('channel', $serverchannel);			    $groupitem->appendChild($channel);
                            $videolayer				= $xml->createElement('videolayer', 20);			            $groupitem->appendChild($videolayer);
                            $delay					= $xml->createElement('delay', 0);							    $groupitem->appendChild($delay);
                            $duration				= $xml->createElement('duration', $meta_row->duration*1000);    $groupitem->appendChild($duration);
                            $allowgpi				= $xml->createElement('allowgpi', 'false');					    $groupitem->appendChild($allowgpi);
                            $allowremotetriggering	= $xml->createElement('allowremotetriggering', 'false');	    $groupitem->appendChild($allowremotetriggering);
                            $remotetriggerid		= $xml->createElement('remotetriggerid');					    $groupitem->appendChild($remotetriggerid);
                            $storyid				= $xml->createElement('storyid', $meta_row->rundown_rows_id);	$groupitem->appendChild($storyid);
                            $flashlayer				= $xml->createElement('flashlayer', 1);						    $groupitem->appendChild($flashlayer);
                            $invoke					= $xml->createElement('invoke');							    $groupitem->appendChild($invoke);
                            $usestoreddata			= $xml->createElement('usestoreddata', 'false');			    $groupitem->appendChild($usestoreddata);
                            $useuppercasedata		= $xml->createElement('useuppercasedata', 'false');			    $groupitem->appendChild($useuppercasedata);
                            $triggeronnext 			= $xml->createElement('triggeronnext', 'false');			    $groupitem->appendChild($triggeronnext);
                            $sendasjson				= $xml->createElement('sendasjson', 'false');				    $groupitem->appendChild($sendasjson);
                    
                            $templatedata			= $xml->createElement('templatedata');						    $groupitem->appendChild($templatedata);

                            if (!is_null($meta_row->data)){
                                $gfxdata = preg_split("/\r\n|\n|\r/", $meta_row->data);
                                foreach ($gfxdata as $data){
                                    $data = explode("=>", $data);
                                    if (is_array($data)){
                                        $componentdata	= $xml->createElement('componentdata');		$templatedata->appendChild($componentdata);
                                        $id				= $xml->createElement('id', $data[0]);			$componentdata->appendChild($id);
                                        $value			= $xml->createElement('value', $data[1]);		$componentdata->appendChild($value);
                                    }	
                                }
                            }
                        }
                    }
                }
            }
        }
        $temp_file = tempnam(sys_get_temp_dir(), 'hda');
		$xml->save($temp_file) or die('XML Create Error');
			header('Content-Description: File Transfer');
			header('Content-Type: application/xml');
			header('Content-Disposition: attachment; filename="'.basename($filename).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($temp_file));
			readfile($temp_file);
			unlink($temp_file);
			exit;
    }
}
