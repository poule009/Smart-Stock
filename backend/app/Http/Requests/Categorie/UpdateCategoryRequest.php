<?php

namespace App\Http\Requests\Categorie;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la mise à jour d'une catégorie.
     */
    public function rules(): array
    {
        $categoryId = $this->route('categorie')->id ?? null;
        
        return [
            'nom' => 'required|string|max:255|unique:categories,nom,' . $categoryId,
        ];
    }

    /**
     * Messages de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la catégorie est requis.',
            'nom.unique' => 'Une catégorie avec ce nom existe déjà.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
        ];
    }
}
