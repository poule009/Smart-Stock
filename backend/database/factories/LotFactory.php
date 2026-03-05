<?php

namespace Database\Factories;

use App\Models\Lot;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class LotFactory extends Factory
{
    protected $model = Lot::class;

    public function definition(): array
    {
        return [
            'produit_id'          => Produit::factory(),
            'quantite_initiale'   => fake()->randomFloat(2, 5, 100),
            'date_expiration'     => fake()->dateTimeBetween('now', '+6 months'),
            'prix_achat_unitaire' => fake()->randomFloat(2, 0.5, 50),
        ];
    }

    /**
     * Après création, assigner quantite_actuelle (non fillable).
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Lot $lot) {
            $lot->quantite_actuelle = fake()->randomFloat(2, 0, $lot->quantite_initiale);
            $lot->save();
        });
    }

    public function expire(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_expiration' => fake()->dateTimeBetween('-3 months', '-1 day'),
        ]);
    }

    public function vide(): static
    {
        return $this->afterCreating(function (Lot $lot) {
            $lot->quantite_actuelle = 0;
            $lot->save();
        });
    }
}
