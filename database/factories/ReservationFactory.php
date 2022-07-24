<?php

namespace Database\Factories;

use App\Helpers\RandomGenerator;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dates = RandomGenerator::instance()->randomDates("-1", "+1");
        return [
            //
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['cancelled' ,'completed', 'pending']),
            'start_time' => $dates[0],
            'end_time' => $dates[1] ?? null
        ];
    }
}
