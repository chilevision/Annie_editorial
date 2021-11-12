<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class Users_controller extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$request->validate([
        	'name' 		=> 'required|max:10|min:3|unique:users|alpha_num',
			'email' 	=> 'required|email|max:255|unique:users',
			'password' 	=> 'required|min:6|confirmed',
		]);
		$user = User::create([
	        'name' 		=> $request->input('name'),
	        'email' 	=> $request->input('email'),
	        'password' 	=> bcrypt($request->input('password')),
	        'admin' 	=> $request->input('admin'),
		]);
        if ($request->input('first') !== null){
            if (!Settings::exists()) {
                Artisan::call('db:seed --class=SettingsSeeder');
            }
            Auth::loginUsingId($user->id);
            return redirect('/dashboard/settings');
        } 
		return redirect('dashboard/settings/users')->with('status','Användaren: "'.$_POST['name'].'" har skapats');
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
        return redirect(route('users'));
    }
}
