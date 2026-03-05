<?php

namespace Database\Seeders;

use App\Models\Mouvement_Stock;
use App\Models\Lot;
use App\Models\User;
use Illuminate\Database\Seeder;

class MouvementStockSeeder extends Seeder
{
    public function run(): void
    {
        $lots = Lot::all();
        $users = User::all();

        for ($i = 0; $i < 50; $i++) {
            $lot = $lots->random();
            $user = $users->random();

            // Choisir un type de mouvement en favorisant les entrées
            $type = fake()->randomElement(['entree', 'entree', 'sortie']);

            // Limiter la quantité de sortie au stock disponible
            if ($type === 'sortie' && $lot->quantite_actuelle < 0.5) {
                $type = 'entree';
            }

            $quantite = ($type === 'sortie')
                ? fake()->randomFloat(2, 0.01, min(10, $lot->quantite_actuelle))
                : fake()->randomFloat(2, 1, 30);

            $raison = ($type === 'entree')
                ? fake()->randomElement(['arrivage fournisseur', 'retour client', 'inventaire correction'])
                : fake()->randomElement(['vente', 'perte', 'péremption', 'casse']);

            // Créer le mouvement
            Mouvement_Stock::create([
                'lot_id'   => $lot->id,
                'user_id'  => $user->id,
                'type'     => $type,
                'quantite' => $quantite,
                'raison'   => $raison,
            ]);

            // Mettre à jour la quantité actuelle du lot
            if ($type === 'entree') {
                $lot->quantite_actuelle += $quantite;
            } else {
                $lot->quantite_actuelle -= $quantite;
            }
            $lot->save();
        }
    }
}
