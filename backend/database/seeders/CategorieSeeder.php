<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Boissons',
            'Viandes',
            'Poissons',
            'Légumes',
            'Fruits',
            'Produits laitiers',
            'Épices',
            'Céréales',
        ];

        foreach ($categories as $nom) {
            Categorie::create(['nom' => $nom]);
        }
    }
}
