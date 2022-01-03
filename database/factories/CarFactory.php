<?php

namespace Database\Factories;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'owner_id' => UserModel::factory()->create()->id,
            'brand' => $this->faker->company(),
            'description' => $this->faker->text(50),
        ];
    }
}
