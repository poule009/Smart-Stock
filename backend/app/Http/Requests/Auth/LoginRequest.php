<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour la connexion.
     */
    public function rules(): array
    {
        return [
            'email'        => 'required|email',
            'mot_de_passe' => 'required|string',
        ];
    }

    /**
     * Messages de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'email.required'           => 'L\'email est requis.',
            'email.email'              => 'L\'email doit être valide.',
            'mot_de_passe.required'    => 'Le mot de passe est requis.',
        ];
    }
}
