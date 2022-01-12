<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Rundown_rows;
use Illuminate\Database\Eloquent\Factories\Factory;

class Rundown_rowsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rundown_rows::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type   = $this->faker->randomElement(['MIXER', 'VB']);
        $audio  = $this->faker->randomElement(['LIVE', 'TAPE', 'TAPE+LIVE']);
        if ($type == 'MIXER'){
            $source     = 'Camera '.random_int(1,10);
            $notes      = $this->faker->realText(100, 2);
            $script     = $this->faker->realText(200, 2);
        }
        else{
            $source     = $this->faker->domainWord();
            $notes      = NULL;
            $script     = NULL;
        }
        return [
            'story'             => $this->faker->realText(50, 1),
            'color'             => substr($this->faker->hexColor(), -6),
            'talent'            => $this->faker->name(),
            'cue'               => $this->faker->realText(25, 1),
            'type'              => $type,
            'source'            => $source,
            'audio'             => $audio,
            'duration'          => random_int(1,180),
            'script'            => $script,
            'cam_notes'         => $notes,
        ];
    }
}
