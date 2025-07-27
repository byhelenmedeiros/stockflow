<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  public function definition()
{
    return [
        'name'     => $this->faker->word,
        'sku'      => strtoupper($this->faker->unique()->bothify('??###')),
        'quantity' => $this->faker->numberBetween(0, 100),
        'price'    => $this->faker->randomFloat(2, 1, 200),
    ];
}
}
