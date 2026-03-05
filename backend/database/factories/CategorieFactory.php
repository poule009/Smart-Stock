<?php

namespace Database\Factories;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategorieFactory extends Factory
{
    protected $model = Categorie::class;

    public function definition(): array
    {
        $categories = [
            'Boissons', 'Viandes', 'Poissons', 'Légumes',
            'Fruits', 'Produits laitiers', 'Épices',
            'Céréales', 'Surgelés', 'Desserts',
            'Huiles', 'Sauces', 'Pâtisserie', 'Snacks'
        ];

        return [
            'nom' => fake()->unique()->randomElement($categories),
        ];
    }
}
