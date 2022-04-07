<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Subfission\Cas\CasManager;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Settings;

class CasController extends Controller
{
    /**
     * Obtain the user information from CAS.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function callback()
    {
        if (cas()->isAuthenticated()){
            $user = User::where('name', '=', cas()->user())->first();
            if ($user === null) {
                $user = User::create([
                    'username'  => cas()->user(),
                    'email'     => cas()->user().'@du.se',
                    'cas'       => 1
                ]);
            }
            Auth::loginUsingId($user->id);
            return redirect(route('dashboard'));
        }
        else{
            dd(cas()->isAuthenticated());
        }
    }

    public function login()
    {
        if (Settings::where('id', 1)->first()->sso){
            return cas()->authenticate();
        }
        else{
            return redirect(route('login'));
        }
    }

    public function logout(Request $request)
    {
        if (Settings::where('id', 1)->first()->sso){
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
            cas()->logout();
        }
    }
}

