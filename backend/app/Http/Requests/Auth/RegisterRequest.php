<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour l'inscription.
     */
    public function rules(): array
    {
        return [
            'nom'           => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'mot_de_passe'  => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Messages de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'nom.required'              => 'Le nom est requis.',
            'email.required'            => 'L\'email est requis.',
            'email.email'               => 'L\'email doit être valide.',
            'email.unique'              => 'Cet email est déjà utilisé.',
            'mot_de_passe.required'     => 'Le mot de passe est requis.',
            'mot_de_passe.min'          => 'Le mot de passe doit faire au moins 8 caractères.',
            'mot_de_passe.confirmed'    => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}
