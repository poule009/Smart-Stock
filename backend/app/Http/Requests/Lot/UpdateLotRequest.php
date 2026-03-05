<?php

namespace App\Http\Requests\Lot;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLotRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la mise à jour d'un lot.
     */
    public function rules(): array
    {
        return [
            'date_expiration' => 'required|date',
            'prix_achat_unitaire' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Messages de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'date_expiration.required' => 'La date d\'expiration est requise.',
            'date_expiration.date' => 'La date d\'expiration doit être une date valide.',
            'prix_achat_unitaire.numeric' => 'Le prix d\'achat doit être un nombre.',
            'prix_achat_unitaire.min' => 'Le prix d\'achat ne peut pas être négatif.',
        ];
    }
}
