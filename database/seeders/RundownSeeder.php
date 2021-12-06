<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rundowns;
use App\Models\Rundown_rows;
use App\Models\Rundown_meta_rows;

class RundownSeeder extends Seeder
{
    public $rundowns    = 50;      //Set this to the amount of rundowns you want to create.
    public $max_users   = 5;       //Set this to the max amount of users for each rundown.
    public $add_rows    = true;    //Set this to true if you want to generate rows to each rundown.
    public $rows        = 10;      //Set this to the amount of rows you want for each rundown.
    public $add_meta    = true;    //Set this to true if you want to generate meta rows to each rundown row.
    public $max_meta    = 5;       //Set this to the maximum amount of meta rows generated for each rundown row.

    //-------- Do not edit below this line! ---------------
    public $position;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rundowns::factory($this->rundowns)->create();
        $users = \App\Models\User::all();
        Rundowns::all()->each(function ($rundown) use ($users){ 
            $rundown->users()->attach(
                $users->random(rand(1, $this->max_users))->pluck('id')->toArray()
            );
            if (!$rundown->users()->find($rundown->owner)) $rundown->users()->attach($rundown->owner);

            if ($this->add_rows){
                $x = 1; 
                while($x <= $this->rows) {
                    if ($x == 1) $this->position = null;
                    else{
                        $this->position = Rundown_rows::where('rundown_id', $rundown->id)->orderBy('before_in_table', 'DESC')->first()->id;
                    }
                    $row = Rundown_rows::factory()->create([
                        'rundown_id'        => $rundown->id,
                        'before_in_table'   => $this->position
                    ]);
        
                    if ($this->add_meta){
                        if (rand(0, 1)) { 
                            Rundown_meta_rows::factory(rand(1, $this->max_meta))->create([
                                'rundown_rows_id'   => $row->id
                            ]);
                        }
                    }
                    $x++;
                }
            }
        });
    }
}