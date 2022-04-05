<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Support\Carbon;

use App\Mail\Notification;
use Illuminate\Support\Facades\Mail;

class MaintainUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:maintain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes unactive users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notifiedUsers = User::where('notified_at', '<=', Carbon::now()->subWeeks(2)->toDateTimeString())->get();
        if ($notifiedUsers){
            $this->deleteUsers($notifiedUsers); 
        }
        $ttl = Settings::where('id', 1)->value('user_ttl');
        if ($ttl){
            $unactiveUsers = User::where('last_signed_in', '<=', Carbon::now()->subMonths($ttl)->toDateTimeString())->get();
            $this->notifyUsers($unactiveUsers);
        }
    }

    protected function deleteUsers($users)
    {
        foreach ($users as $user){
            User::find($user->id)->delete();
        }
    }

    protected function notifyUsers($users)
    {
        foreach ($users as $user){
            $token = bin2hex(random_bytes(16));
            User::where('id', $user->id)->update([
                'notified_at'   => Carbon::now()->toDateTimeString(),
                'email_token'   => $token
            ]);
            Mail::to($user->email)->send(new Notification(User::find($user->id)));
        }
    }
}
