<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rundowns;

class RundownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rundowns::factory(50)->create();
        $users = \App\Models\User::all();
        Rundowns::all()->each(function ($rundown) use ($users){ 
            $rundown->users()->attach(
                $users->random(rand(1, 3))->pluck('id')->toArray()
            );
            if (!$rundown->users()->find($rundown->owner)) $rundown->users()->attach($rundown->owner);
        });
    }
}