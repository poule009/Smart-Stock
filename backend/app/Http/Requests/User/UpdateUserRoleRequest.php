<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRoleRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation pour le changement de rôle.
     */
    public function rules(): array
    {
        return [
            'role' => 'required|in:admin,cuisinier,serveur',
        ];
    }

    /**
     * Messages de validation personnalisés.
     */
    public function messages(): array
    {
        return [
            'role.required' => 'Le rôle est requis.',
            'role.in'       => 'Le rôle doit être admin, cuisinier ou serveur.',
        ];
    }
}
