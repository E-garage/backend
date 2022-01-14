<?php

namespace Database\Factories;


use App\Models\Car;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;


class RefuelingFactory extends Factory
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
            'car_id' => Car::factory()->create()->id,
            'date' => $this->faker->date,
            'FuelType' => $this->faker->text,
            'amount' => $this->faker->randomNumber(3),
            'TotalPrice' => $this->faker->randomNumber(4),
        ];
    }

}
