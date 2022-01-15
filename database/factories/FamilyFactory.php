<?php

namespace Database\Factories;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class FamilyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owner_id' => UserModel::factory()->create()->id,
            'name' => $this->faker->company(),
            'description' => $this->faker->words(5, true),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
