<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Email_controller extends Controller
{
    public function test()
    {
        return (new Notification(Auth::user()));
    }

    public function view($id, $token)
    {
        $user = User::find($id);
        if ($user->email_token == $token){
            return (new Notification($user));
        }else{
            return redirect(route('home'))->withErrors(__('app.permission_denied'));
        }
    }
}
