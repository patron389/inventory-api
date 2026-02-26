<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'sku' => strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . rand(100, 999),
            'category' => $this->faker->randomElement([
                'Laptop',
                'Accessories',
                'Furniture',
                'Electronics',
                'Office Supplies'
            ]),
            'unit' => 'pcs',
            'price' => $this->faker->numberBetween(500, 100000),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}