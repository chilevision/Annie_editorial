<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = ['930000', '47295e', 'e05400', '007792', '718815', 'da8f00', '003a48', '5c220a', '054334', '870058'];
        Settings::create([
            'name'                  => 'Annie Editorial',
            'max_rundown_lenght'    => 240,
            'colors'                => serialize($colors),
        ]);
    }
}
