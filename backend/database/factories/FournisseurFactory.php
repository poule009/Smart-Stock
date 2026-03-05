<?php

namespace Database\Factories;

use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Factories\Factory;

class FournisseurFactory extends Factory
{
    protected $model = Fournisseur::class;

    public function definition(): array
    {
        return [
            'nom'       => fake()->company(),
            'email'     => fake()->unique()->companyEmail(),
            'telephone' => fake()->phoneNumber(),
            'adresse'   => fake()->address(),
        ];
    }
}
