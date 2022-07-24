<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Helpers\RandomGenerator;
use App\Models\User;

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
            'document' => RandomGenerator::instance()->randomNumberString(11),
            'card' => self::newRandomCard(),
            'birthday' => RandomGenerator::instance()->randomBirthdayDate('-30','-20'),
            'plan' => $this->faker->randomElement(['basic' ,'pro', 'preminum']),
        ];
    }

    private function newRandomCard(){
        $card = RandomGenerator::instance()->randomNumberString(10); 
        $isUsed =  User::where('card', $card)->first();
        if ($isUsed) {
            return $this->newRandomCard();
        }
        return $card;
    }


}
