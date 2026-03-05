<?php

namespace App\Http\Requests\MouvementStock;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMouvementStockRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la mise à jour d'un mouvement de stock.
     */
    public function rules(): array
    {
        return [
            'lot_id' => ['required', Rule::exists('lots', 'id')->whereNull('deleted_at')],
            'type' => 'required|in:entree,sortie',
            'quantite' => 'required|numeric|min:0.01',
            'raison' => 'required|string|max:255',
        ];
    }

    /**
     * Messages de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'lot_id.required' => 'Le lot est requis.',
            'lot_id.exists' => 'Le lot sélectionné n\'existe pas.',
            'type.required' => 'Le type de mouvement est requis.',
            'type.in' => 'Le type doit être "entree" ou "sortie".',
            'quantite.required' => 'La quantité est requise.',
            'quantite.numeric' => 'La quantité doit être un nombre.',
            'quantite.min' => 'La quantité doit être supérieure à 0.',
            'raison.required' => 'La raison du mouvement est requise.',
            'raison.max' => 'La raison ne peut pas dépasser 255 caractères.',
        ];
    }
}
