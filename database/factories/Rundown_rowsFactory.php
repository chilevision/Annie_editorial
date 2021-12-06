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
        ($type == 'MIXER') ? $source = 'CAM'.random_int(1,10) : $source = $this->faker->domainWord();
        return [
            'story'             => $this->faker->realText(50, 1),
            'color'             => substr($this->faker->hexColor(), -6),
            'talent'            => $this->faker->name(),
            'cue'               => $this->faker->realText(25, 1),
            'type'              => $type,
            'source'            => $source,
            'audio'             => $audio,
            'duration'          => random_int(1,180)
        ];
    }
}
