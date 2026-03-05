<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mot_de_passe' => 'hashed',
        ];
    }

    /**
     * Dire à Laravel quel est le champ du mot de passe
     * Par défaut Laravel cherche 'password', mais nous on a 'mot_de_passe'
     */
    public function getAuthPassword(): string
    {
        return $this->mot_de_passe;
    }

    public function mouvementStocks(): HasMany
    {
        return $this->hasMany(Mouvement_Stock::class);
    }
}
