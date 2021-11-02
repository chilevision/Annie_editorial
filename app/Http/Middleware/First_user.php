<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class First_user
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        {
            if (!Schema::hasTable('users')) {
                Artisan::call('migrate');
            }
            if (User::exists()) {
                return redirect('/login');
            } else {
                return $next($request);
            }
        }
    }
}
