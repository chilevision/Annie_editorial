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
        $settings               = Settings::where('id', 1)->first();
        $settings->userValPage  = ['subject', 'emailBody'];
        $settings->pusherEnv = 0;
        if (env('PUSHER_APP_ID') && env('PUSHER_APP_KEY') && env('PUSHER_APP_SECRET') && env('PUSHER_APP_CLUSTER')){
            $settings->pusherEnv = 1;
        }
        $roles = json_decode($settings->user_roles);
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
            'userTTL'   => $ttlOptions,
            'roles'     => $roles,
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
            'name'                      => 'required|max:30',
            'image'                     => 'image|mimes:png,jpg,jpeg,gif|max:5048',
            'max_rundown_lenght'        => 'required|numeric|min:1',
            'company'                   => 'nullable|max:30|regex:/^[\pL\s\-]+$/u',
            'company_phone'             => 'nullable|max:20|min:6|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'company_email'             => 'nullable|email',
            'videoserver_name'          => 'nullable|max:30|regex:/^[\pL\s\-]+$/u',
            'videoserver_ip'            => 'nullable|ip',
            'videoserver_port'          => 'nullable|numeric|max:9999',
            'videoserver_channel'       => 'nullable|numeric|max:9|gt:0',
            'templateserver_name'       => 'nullable|max:30|regex:/^[\pL\s\-]+$/u',
            'templateserver_ip'         => 'nullable|ip',
            'templateserver_port'       => 'nullable|numeric|max:9999',
            'templateserver_channel'    => 'nullable|numeric|max:9|gt:0',
            'backgroundserver_channel'  => 'nullable|numeric|max:9|gt:0'
        ]);
        $colors         = [];
        $mixer_inputs   = [];
        $mixer_keys     = [];
        $request->input('sso') ? $sso = 1 : $sso = 0;
        $request->input('include_background') ? $bg = 1 : $bg = 0;
        $request->input('include_delay') ? $delay = 1 : $delay = 0;
        foreach ($request->input() as $key=>$input){
            if (strpos($key, 'color') === 0)        array_push($colors, substr($input, -6));
            if (strpos($key, 'mixer_input') === 0)  array_push($mixer_inputs, $input);
            if (strpos($key, 'mixer_key') === 0)    array_push($mixer_keys, $input);
        }

        if ($request->input('ttl')){
            $request->validate([
                'senderEmail' => 'required|email',
                'senderName'    => 'required|max:30|regex:/^[\pL\s\-]+$/u',
                'subject'       => 'required',
                'emailBody'     => 'required',
            ]);
        }
        $request->input('roles') == '[]' ? $roles = null : $roles = $request->input('roles');
        Settings::where('id', 1)->update([
            'name'                      => $request->input('name'),
            'company'                   => $request->input('company'),
            'company_address'           => $request->input('company_address'),
            'company_country'           => $request->input('company_country'),
            'company_phone'             => $request->input('company_phone'),
            'company_email'             => $request->input('company_email'),
            'max_rundown_lenght'        => $request->input('max_rundown_lenght'),
            'videoserver_name'          => $request->input('videoserver_name'),
            'videoserver_ip'            => $request->input('videoserver_ip'),
            'videoserver_port'          => $request->input('videoserver_port'),
            'videoserver_channel'       => $request->input('videoserver_channel'),
            'templateserver_name'       => $request->input('templateserver_name'),
            'templateserver_ip'         => $request->input('templateserver_ip'),
            'templateserver_port'       => $request->input('templateserver_port'),
            'templateserver_channel'    => $request->input('templateserver_channel'),
            'backgroundserver_channel'  => $request->input('backgroundserver_channel'),
            'include_background'        => $bg,
            'include_background'        => $delay,
            'pusher_channel'            => $request->input('pusher_channel'),
            'colors'                    => serialize($colors),
            'sso'                       => $sso,
            'user_ttl'                  => $request->input('ttl'),
            'mixer_inputs'              => serialize($mixer_inputs),
            'mixer_keys'                => serialize($mixer_keys),
            'email_address'             => $request->input('senderEmail'),
            'email_name'                => $request->input('senderName'),
            'email_subject'             => $request->input('subject'),
            'removal_email_body'        => $request->input('emailBody'),
            'user_roles'                => $roles,
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

        return redirect(route('settings'))->with('status', __('settings.updated'));
    }
}
