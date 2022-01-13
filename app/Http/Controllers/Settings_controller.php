<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Image;

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
        $ttlOptions = [
            ['value' => '0', 'title' => __('settings.never')],
            ['value' => '6', 'title' => '6 '.__('settings.months')],
            ['value' => '12', 'title' => '12 '.__('settings.months')],
            ['value' => '24', 'title' => '24 '.__('settings.months')],
            ['value' => '36', 'title' => '36 '.__('settings.months')]
        ];
        $colors         = unserialize($settings->colors);
        $mixer_inputs   = unserialize($settings->mixer_inputs);
        $mixer_keys     = unserialize($settings->mixer_keys);
        if (!$mixer_inputs) $mixer_inputs = [];
        if (!$mixer_keys) $mixer_keys = [];
        return view('settings.settings')->with([
            'settings'  => $settings,
            'colors'    => $colors,
            'inputs'    => $mixer_inputs,
            'keys'      => $mixer_keys,
            'userTTL'   => $ttlOptions
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
        $request->validate([
            'name'          => 'required|max:30',
            'image'         => 'image|mimes:png,jpg,jpeg,gif|max:5048',
            'showlenght'    => 'required|numeric|min:1',
        ]);
        $colors         = [];
        $mixer_inputs   = [];
        $mixer_keys     = [];
        $sso            = 0;
        if ($request->input('sso')) $sso = 1;
        foreach ($request->input() as $key=>$input){
            if (strpos($key, 'color') === 0)        array_push($colors, substr($input, -6));
            if (strpos($key, 'mixer_input') === 0)  array_push($mixer_inputs, $input);
            if (strpos($key, 'mixer_key') === 0)    array_push($mixer_keys, $input);
        }
        Settings::where('id', 1)->update([
            'name'                      => $request->input('name'),
            'max_rundown_lenght'        => $request->input('showlenght'),
            'videoserver_name'          => $request->input('vserver_name'),
            'videoserver_ip'            => $request->input('vserver_ip'),
            'videoserver_port'          => $request->input('vserver_port'),
            'videoserver_channel'       => $request->input('vserver_channel'),
            'templateserver_name'       => $request->input('gfxserver_name'),
            'templateserver_ip'         => $request->input('gfxserver_ip'),
            'templateserver_port'       => $request->input('gfxserver_port'),
            'templateserver_channel'    => $request->input('gfxserver_channel'),
            'pusher_channel'            => $request->input('pusher_channel'),
            'colors'                    => serialize($colors),
            'sso'                       => $sso,
            'user_ttl'                  => $request->input('ttl'),
            'mixer_inputs'              => serialize($mixer_inputs),
            'mixer_keys'                => serialize($mixer_keys)
        ]);

        if($request->file('image')){
            $filename = time() . '-logo.' . $request->image->extension();
            $storage_folder = base_path().'/resources/uploads';
            $public_folder  = public_path('site_logo');

            $files = glob($storage_folder.'/*'); 
            // Deleting all the files in the list
            foreach($files as $file) {
                if(is_file($file)) 
                    // Delete the given file
                    unlink($file); 
            }
            $files = glob($public_folder.'/*'); 
            // Deleting all the files in the list
            foreach($files as $file) {
                if(is_file($file)) 
                    // Delete the given file
                    unlink($file); 
            }
            $request->image->move(base_path().'/resources/uploads', $filename);

            // open an image file
            $img = Image::make(base_path().'/resources/uploads/'.$filename);

            // resize image instance
            $img->resize(240, 60, function ($const) {
                $const->aspectRatio();
            })->save($public_folder.'/'.$filename);


            Settings::where('id', 1)->update(['logo_path' => $filename]);
        }

        return redirect(route('settings'));
    }
    
}
