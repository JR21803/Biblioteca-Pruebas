<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'author' => fake()->name(),
            'title' => fake()->sentence(),
            'description' => fake()->text(),
            'ISBN' => fake()->isbn13(),
            'total_copies' => fake()->numberBetween(1,10),
            'available_copies' => fake()->numberBetween(1,10),
            'is_available' => true
];
    }
}