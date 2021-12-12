<?php

namespace Database\Factories;

use App\Models\Rundowns;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class RundownsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rundowns::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {        
        $starttime = $this->faker->unique()->dateTimeThisYear();
        $stoptime = Carbon::parse($starttime)->addMinutes(30);
        return [
            'title'     => $this->faker->realText(50, 1),
            'owner'     => random_int(1, 10),
            'starttime' => $starttime,
            'stoptime'  => $stoptime,
            'duration'  => 1800,
        ];
    }
}
