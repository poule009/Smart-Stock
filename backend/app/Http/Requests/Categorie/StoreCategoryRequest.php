<?php

namespace App\Http\Requests\Categorie;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la création d'une catégorie.
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255|unique:categories,nom',
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
