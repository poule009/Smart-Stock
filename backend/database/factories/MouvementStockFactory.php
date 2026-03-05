<?php

namespace Database\Factories;

use App\Models\Mouvement_Stock;
use App\Models\Lot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MouvementStockFactory extends Factory
{
    protected $model = Mouvement_Stock::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['entree', 'sortie']);

        return [
            'lot_id'   => Lot::factory(),
            'user_id'  => User::factory(),
            'type'     => $type,
            'quantite' => fake()->randomFloat(2, 1, 30),
            'raison'   => $type === 'entree'
                ? fake()->randomElement(['arrivage fournisseur', 'retour client', 'inventaire correction'])
                : fake()->randomElement(['vente', 'perte', 'péremption', 'casse']),
        ];
    }
}
