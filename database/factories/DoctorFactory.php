<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    public function userId($user)
    {
        return $this->state([
            'user_id' => $user->id,
        ]);
    }

    public function definition(): array
    {
        return [
            'specialty' =>
            $this->faker->sentence(5, true),
            'ratings' => $this->faker->randomDigit(1)
        ];
    }
}
