<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Business>
 */
class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition(): array
    {
        $name = fake()->company();

        return [
            'user_id' => User::factory()->business(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 9999),
            'type' => fake()->randomElement(['Estetica', 'Barberia', 'Spa', 'Uñas']),
            'phone' => fake()->numerify('3#########'),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->address(),
            'primary_color' => '#994b35',
        ];
    }
}
