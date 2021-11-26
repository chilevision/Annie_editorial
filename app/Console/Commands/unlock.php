<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rundown_rows;
use App\Models\Rundown_meta_rows;
use Carbon\Carbon;
use App\Events\RundownEvent;

class unlock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unlock:unlock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlocks all locked rows and meta rows if older then 5 minutes.';

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
        $counter = 0;
        $locked_rows = Rundown_rows::where('locked_at', '<', Carbon::now()->subMinutes(5))->get();
        foreach ($locked_rows as $row){
            Rundown_rows::where('id', $row->id)->update([
                'locked_at'    => null,
                'locked_by'    => null
            ]);
            event(new RundownEvent(['type' => 'row', 'id' => $row->id, 'lock' => 0], $row->rundown_id));
        }
        $locked_scripts = Rundown_rows::where('script_locked_at', '<', Carbon::now()->subMinutes(5))->get();
        foreach ($locked_scripts as $script){
            Rundown_rows::where('id', $script->id)->update([
                'script_locked_at'    => null,
                'script_locked_by'    => null
            ]);
            event(new RundownEvent(['type' => 'script', 'id' => $script->id, 'lock' => 0], $script->rundown_id));
        }
        $locked_notes = Rundown_rows::where('notes_locked_at', '<', Carbon::now()->subMinutes(5))->get();
        foreach ($locked_notes as $notes){
            Rundown_rows::where('id', $notes->id)->update([
                'notes_locked_at'    => null,
                'notes_locked_by'    => null
            ]);
            event(new RundownEvent(['type' => 'cam_notes', 'id' => $notes->id, 'lock' => 0], $notes->rundown_id));
        }
        $locked_meta_rows = Rundown_meta_rows::where('locked_at', '<', Carbon::now()->subMinutes(5))->get();
        foreach ($locked_meta_rows as $row){
            Rundown_meta_rows::where('id', $row->id)->update([
                'locked_at'    => null,
                'locked_by'    => null
            ]);
            $rundown_id = Rundown_rows::where('id', $row->rundown_rows_id)->first()->rundown_id;
            event(new RundownEvent(['type' => 'meta_row', 'id' => $row->id, 'lock' => 0], $rundown_id));
        }
        //$counter = $counter + $locked_rows->count + $locked_scripts->count + $locked_notes->count + $locked_meta_rows->count;
        //return Carbon::now() . ' : unlocked ' . $counter . ' rows' . "\n";
    }
}
