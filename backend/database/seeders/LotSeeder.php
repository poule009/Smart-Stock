<?php

namespace Database\Seeders;

use App\Models\Lot;
use App\Models\Produit;
use Illuminate\Database\Seeder;

class LotSeeder extends Seeder
{
    public function run(): void
    {
        $produits = Produit::all();

        // Chaque produit aura entre 1 et 4 lots
        foreach ($produits as $produit) {
            $nombreLots = rand(1, 4);

            Lot::factory()
                ->count($nombreLots)
                ->create([
                    'produit_id' => $produit->id,
                ]);
        }

        // Ajouter quelques lots expirés pour tester les alertes
        Lot::factory()
            ->count(5)
            ->expire()
            ->create([
                'produit_id' => fn () => $produits->random()->id,
            ]);
    }
}
