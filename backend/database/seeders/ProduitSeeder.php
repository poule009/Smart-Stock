<?php

namespace Database\Seeders;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Fournisseur;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Categorie::all();
        $fournisseurs = Fournisseur::all();

        Produit::factory()
            ->count(25)
            ->create([
                'categorie_id'   => fn () => $categories->random()->id,
                'fournisseur_id' => fn () => $fournisseurs->random()->id,
            ]);
    }
}
