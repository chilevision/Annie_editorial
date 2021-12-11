<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use OCILob;

class Users_controller extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function create()
    {
        return view('users.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $admin = 0;
        if ($request->exists('admin')) $admin = 1;
    	$request->validate([
        	'name' 		=> 'required|max:10|min:3|unique:users|alpha_num',
			'email' 	=> 'required|email|max:255|unique:users',
			'password' 	=> 'required|min:6|confirmed',
		]);
		$user = User::create([
	        'name' 		=> $request->input('name'),
	        'email' 	=> $request->input('email'),
	        'password' 	=> bcrypt($request->input('password')),
	        'admin' 	=> $admin,
		]);
        if ($request->input('first') !== null){
            if (!Settings::exists()) {
                Artisan::call('db:seed --class=SettingsSeeder');
            }
            Auth::loginUsingId($user->id);
            return redirect('/dashboard/settings');
        } 
		return redirect(route('users.index'))->with('status', __('app.user').' "'.$request->input('name').'"'.__('app.user-created'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('users.edit')->with('user', $user);
    }

    public function update(Request $request, $id)
    {
        $admin = 0;
        if ($request->exists('admin')) $admin = 1;
        $request->validate([
        	'name' 		=> 'required|max:10|min:3|unique:users,name,'.$id.'|alpha_num',
			'email' 	=> 'required|email|max:255|unique:users,email,'.$id,
		]);
        if ($request->input('password') !=null){
            $request->validate([
                'password' 	=> 'required|min:6|confirmed',
            ]);
            User::find($id)->update([
                'name'      => $request->input('name'),
                'email'     => $request->input('email'),
                'password'  => bcrypt($request->input('password')),
                'admin'     => $admin
            ]);
        }
        else{
            User::find($id)->update([
                'name'      => $request->input('name'),
                'email'     => $request->input('email'),
                'admin'     => $admin
            ]);
        }
        return redirect(route('users.index'))->with('status', __('app.user').' "'.$request->input('name').'"'.__('app.user-updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect(route('users.index'));
    }
}
