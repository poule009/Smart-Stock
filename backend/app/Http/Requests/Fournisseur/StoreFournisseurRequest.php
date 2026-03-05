<?php

namespace App\Http\Requests\Fournisseur;

use Illuminate\Foundation\Http\FormRequest;

class StoreFournisseurRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la création d'un fournisseur.
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:fournisseurs,email',
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string|max:500',
        ];
    }

    /**
     * Messages de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du fournisseur est requis.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'email du fournisseur est requis.',
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.unique' => 'Un fournisseur avec cet email existe déjà.',
            'telephone.required' => 'Le téléphone du fournisseur est requis.',
            'telephone.max' => 'Le téléphone ne peut pas dépasser 20 caractères.',
            'adresse.max' => 'L\'adresse ne peut pas dépasser 500 caractères.',
        ];
    }
}
