<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rundown_rows;
use App\Models\Rundown_meta_rows;

class RundownRowSeeder extends Seeder
{
    public $rundown_id  = 4;        //Set this to the id of the rundown you want to add rows to. 
    public $rows        = 10;       //Set this to the amount of rows you want for each rundown.
    public $add_meta    = true;     //Set this to true if you want to generate meta rows to each rundown row.
    public $max_meta    = 5;        //Set this to the maximum amount of meta rows generated for each rundown row.

    //-------- Do not edit below this line! ---------------
    public $position;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows_count = Rundown_rows::where('rundown_id', $this->rundown_id)->count();
        for ($i = 1; $i <= $this->rows; $i++) {
            if ($rows_count == 0) $this->position = null;
            else{
                $this->position = Rundown_rows::where('rundown_id', $this->rundown_id)->orderBy('before_in_table', 'DESC')->first()->id;
            }
            $row = Rundown_rows::factory()->create([
                'rundown_id'        => $this->rundown_id,
                'before_in_table'   => $this->position
            ]);

            if ($this->add_meta){
                if (rand(0, 1)) { 
                    Rundown_meta_rows::factory(rand(1, $this->max_meta))->create([
                        'rundown_rows_id'   => $row->id
                    ]);
                }
            }
        }
    }
}