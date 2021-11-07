<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class Settings_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings       = Settings::where('id', 1)->first();
        $colors         = unserialize($settings->colors);
        $mixer_inputs   = unserialize($settings->mixer_inputs);
        $mixer_keys     = unserialize($settings->mixer_keys);
        if (!$mixer_inputs) $mixer_inputs = [];
        if (!$mixer_keys) $mixer_keys = [];
        return view('settings.settings')->with([
            'settings'  => $settings,
            'colors'    => $colors,
            'inputs'    => $mixer_inputs,
            'keys'      => $mixer_keys
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $colors         = [];
        $mixer_inputs   = [];
        $mixer_keys     = [];
        foreach ($request->input() as $key=>$input){
            if (strpos($key, 'color') === 0)        array_push($colors, $input);
            if (strpos($key, 'mixer_input') === 0)  array_push($mixer_inputs, $input);
            if (strpos($key, 'mixer_key') === 0)    array_push($mixer_keys, $input);
        }
        Settings::where('id', 1)->update([
            'name'                      => $request->input('name'),
            'max_rundown_lenght'        => $request->input('showlenght'),
            'videoserver_ip'            => $request->input('vserver_ip'),
            'videoserver_port'          => $request->input('vserver_port'),
            'templateserver_ip'         => $request->input('gfxserver_ip'),
            'templateserver_port'       => $request->input('gfxserver_port'),
            'pusher_channel'            => $request->input('pusher_channel'),
            'colors'                    => serialize($colors),
            'mixer_inputs'              => serialize($mixer_inputs),
            'mixer_keys'                => serialize($mixer_keys)
        ]);

        return redirect(route('settings'));
    }
}