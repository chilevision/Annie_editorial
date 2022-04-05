<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Carbon\Carbon;
use App\Models\User;

class UserLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        User::where('id', $event->user->id)->update([
            'last_signed_in'    => Carbon::now()->toDateTimeString(),
            'notified_at'       => null
        ]);
    }
}
