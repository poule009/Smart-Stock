<?php

namespace App\Http\Requests\Lot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLotRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la création d'un lot.
     */
    public function rules(): array
    {
        return [
            'produit_id' => ['required', Rule::exists('produits', 'id')->whereNull('deleted_at')],
            'quantite_initiale' => 'required|numeric|min:0.01',
            'date_expiration' => 'required|date|after:today',
            'prix_achat_unitaire' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Messages de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'produit_id.required' => 'Le produit est requis.',
            'produit_id.exists' => 'Le produit sélectionné n\'existe pas.',
            'quantite_initiale.required' => 'La quantité initiale est requise.',
            'quantite_initiale.numeric' => 'La quantité initiale doit être un nombre.',
            'quantite_initiale.min' => 'La quantité initiale doit être supérieure à 0.',
            'date_expiration.required' => 'La date d\'expiration est requise.',
            'date_expiration.date' => 'La date d\'expiration doit être une date valide.',
            'date_expiration.after' => 'La date d\'expiration doit être dans le futur.',
            'prix_achat_unitaire.numeric' => 'Le prix d\'achat doit être un nombre.',
            'prix_achat_unitaire.min' => 'Le prix d\'achat ne peut pas être négatif.',
        ];
    }
}
