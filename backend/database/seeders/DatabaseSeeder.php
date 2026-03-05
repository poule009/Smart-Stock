<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer un admin par défaut (mot de passe connu)
        User::factory()->admin()->create([
            'nom'          => 'Admin',
            'email'        => 'admin@smartstock.com',
            'mot_de_passe' => 'password',
        ]);

        // 2. Créer quelques utilisateurs supplémentaires
        User::factory()->count(4)->create();

        // 3. Lancer les seeders dans le bon ordre
        $this->call([
            CategorieSeeder::class,
            FournisseurSeeder::class,
            ProduitSeeder::class,
            LotSeeder::class,
            MouvementStockSeeder::class,
        ]);
    }
}
