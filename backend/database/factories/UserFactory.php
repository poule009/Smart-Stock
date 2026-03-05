<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nom'          => fake()->name(),
            'email'        => fake()->unique()->safeEmail(),
            'mot_de_passe' => static::$password ??= Hash::make('password'),
            'role'         => fake()->randomElement(['admin', 'cuisinier', 'serveur']),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
