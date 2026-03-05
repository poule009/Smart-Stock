<?php

namespace Database\Factories;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitFactory extends Factory
{
    protected $model = Produit::class;

    public function definition(): array
    {
        return [
            'categorie_id'   => Categorie::factory(),
            'fournisseur_id' => Fournisseur::factory(),
            'nom'            => fake()->words(2, true),
            'unite'          => fake()->randomElement(['kg', 'litre', 'unité', 'portion', 'pièce']),
            'seuil_alerte'   => fake()->numberBetween(3, 20),
        ];
    }
}
