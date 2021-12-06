<?php

namespace Database\Factories;

use App\Models\Rundown_meta_rows;
use Illuminate\Database\Eloquent\Factories\Factory;

class Rundown_meta_rowsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rundown_meta_rows::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['AUDIO', 'GFX', 'KEY', 'BG']);
        $bgType = $this->faker->randomElement(['SCREEN', 'MONITOR', 'GREEN-SCREEN']);       
        switch ($type){
            case 'AUDIO' : 
                $title = 'AUDIO SCENE SWITCH';
                $source = 'scene: '.random_int(1, 10);
            break;
            case 'GFX' :
                $title = $this->faker->name().' LOWER THIRD';
                $source = 'NEWS_LOWER_THIRD 0'.random_int(1, 9);
            break;
            case 'KEY' :
                $title = 'PIP KEY 0'.random_int(1, 8);
                $source = 'KEY-'.random_int(1, 4);
            break;
            case 'BG' :
                $title = $this->faker->realText(20, 1).' BACKGROUND';  
                $source = 'KEY-'.random_int(1, 4);
                $type == $bgType;
            break;
        }
        return [
            'title'             => $title,
            'type'              => $type,
            'source'            => $source,
            'delay'             => random_int(0, 179),
            'duration'          => random_int(1, 999999)
        ];
    }
}