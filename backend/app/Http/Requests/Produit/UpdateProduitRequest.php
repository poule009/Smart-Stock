<?php

namespace App\Http\Requests\Produit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProduitRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la mise à jour d'un produit.
     */
    public function rules(): array
    {
        return [
            'nom' => 'sometimes|required|string|max:255',
            'categorie_id' => ['sometimes', 'required', Rule::exists('categories', 'id')->whereNull('deleted_at')],
            'fournisseur_id' => ['sometimes', 'nullable', Rule::exists('fournisseurs', 'id')->whereNull('deleted_at')],
            'unite' => 'sometimes|required|string|max:50',
            'seuil_alerte' => 'sometimes|required|integer|min:0',
        ];
    }

    /**
     * Messages de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du produit est requis.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'categorie_id.required' => 'La catégorie est requise.',
            'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'fournisseur_id.exists' => 'Le fournisseur sélectionné n\'existe pas.',
            'unite.required' => 'L\'unité est requise.',
            'unite.max' => 'L\'unité ne peut pas dépasser 50 caractères.',
            'seuil_alerte.required' => 'Le seuil d\'alerte est requis.',
            'seuil_alerte.integer' => 'Le seuil d\'alerte doit être un entier.',
            'seuil_alerte.min' => 'Le seuil d\'alerte ne peut pas être négatif.',
        ];
    }
}
