<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Users_controller extends Controller
{
    public function index()
    {
        if (Auth::user()->admin){
            return view('users.index');
        }
        else{
            return redirect(route('dashboard'));
        }
    }

    public function create()
    {
        if (Auth::user()->admin){
            return view('users.create');
        }
        else{
            return redirect(route('dashboard'));
        }
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
        	'username' 	=> 'required|max:10|min:3|unique:users|alpha_num',
			'email' 	=> 'required|email|max:255|unique:users',
			'password' 	=> 'required|min:6|confirmed',
		]);
		$user = User::create([
	        'name' 		=> $request->input('name'),
            'username'  => $request->input('username'),
            'phone'     => $request->input('phone'),
	        'email' 	=> $request->input('email'),
            'role'      => $request->input('role'),
	        'password' 	=> bcrypt($request->input('password')),
	        'admin' 	=> $admin,
		]);
        if ($request->input('first') !== null){
            if (!Settings::exists()) {
                Artisan::call('db:seed --class=SettingsSeeder --force');
            }
            Auth::loginUsingId($user->id);
            return redirect('/dashboard/settings');
        } 
		return redirect(route('users.index'))->with('status', __('app.user').' "'.$request->input('name').'"'.__('app.user-created'));
    }

    public function edit($id)
    {
        if (Auth::user()->admin || Auth::user()->id == $id){
            $user = User::find($id);
            $roles = json_decode(Settings::where('id', 1)->first()->user_roles);
            $optionRoles = [['value' => null, 'title' => '']];
            foreach ($roles as $role){
                array_push($optionRoles, ['value' => $role, 'title' => $role]);
            }
            return view('users.edit')->with(['user' => $user, 'roles' => $optionRoles]);
        }
        else{
            return redirect(route('dashboard'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->admin || Auth::user()->id == $id){
            $admin = 0;
            if ($request->exists('admin')) $admin = 1;
            $request->validate([
                'username' 	=> 'required|max:10|min:3|unique:users,username,'.$id.'|alpha_num',
                'email' 	=> 'required|email|max:255|unique:users,email,'.$id,
                'name'      => 'nullable|max:30|regex:/^[\pL\s\-]+$/u',
                'phone'     => 'nullable|max:20|min:6|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'password'  => 'nullable|min:6|confirmed'
            ]);
            if ($request->input('password') !=null){
                User::find($id)->update([
                    'name'      => $request->input('name'),
                    'username'  => $request->input('username'),
                    'phone'     => $request->input('phone'),
                    'email'     => $request->input('email'),
                    'role'      => $request->input('role'),
                    'password'  => bcrypt($request->input('password')),
                    'admin'     => $admin
                ]);
            }
            else{
                User::find($id)->update([
                    'name'      => $request->input('name'),
                    'username'  => $request->input('username'),
                    'phone'     => $request->input('phone'),
                    'email'     => $request->input('email'),
                    'role'      => $request->input('role'),
                    'admin'     => $admin
                ]);
            }
            return redirect(route('users.index'))->with('status', __('app.user').' "'.$request->input('name').'"'.__('app.user-updated'));
        }
        else{
            return redirect(route('dashboard'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->id == $id) $myselfe = 1;
        if (Auth::user()->admin || isset($myselfe)){
            User::find($id)->delete();
            if (isset($myselfe)){
                Session::flush();
                Auth::logout();
                return redirect('login');
            }
            else{
                return redirect(route('users.index'));
            }
        }
        else{
            return redirect(route('dashboard'));
        }
    }
}
