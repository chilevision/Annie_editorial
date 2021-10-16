<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\User;
use Illuminate\Support\Facades\Auth;

class Users_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
            Auth::loginUsingId($user->id);
            return redirect('/dashboard');
        } 
		return redirect('dashboard/settings/users')->with('status','Användaren: "'.$_POST['name'].'" har skapats');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
