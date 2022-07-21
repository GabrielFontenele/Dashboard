<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'document' => self::randomNumberString(11),
            'card' => self::randomNumberString(10),
            'birthday' => self::randomBirthdayDate(),
            'plan' => $this->faker->randomElement(['basic' ,'pro', 'preminum']),
        ];
    }

    function randomNumberString($length)
    {
        return  substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)) )),1,$length);
    }

    function randomBirthdayDate()
    {
        $start_date = strtotime(date('d-m-Y', strtotime("-30 year")));
        $end_date = strtotime(date('d-m-Y', strtotime("-20 year")));

        return date('Y-m-d H:i:s', rand($start_date, $end_date));
    }
}
