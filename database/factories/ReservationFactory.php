<?php

namespace Database\Factories;

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
        $dates = self::randomDate();
        return [
            //
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['cancelled' ,'completed', 'pending']),
            'start_time' => $dates[0],
            'end_time' => $dates[1] ?? null
        ];
    }

    function randomDate()
    {
        $dates = [];
        $startDate = strtotime(date('d-m-Y', strtotime("-1 week")));
        $endDate = strtotime(date('d-m-Y', strtotime("+1 week")));
        $startTime = date('Y-m-d H:i:s', rand($startDate, $endDate));

        $startTime = DateTime::createFromFormat('Y-m-d H:i:s', $startTime);

        $startTime->setTime(rand(9, 19), rand(0, 1) ? 30 : 0);
        $dates[] = $startTime;
        if (!rand(0, 4)){
            $endTime = clone $startTime;
            $h = $endTime->format('H');
            $i = $endTime->format('i');
            $h++;
            $dates[] = $endTime->setTime($h, $i);
        }
        return $dates;

    }
    
}
