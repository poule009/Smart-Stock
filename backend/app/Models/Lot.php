<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lot extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'produit_id',
        'quantite_initiale',
        'date_expiration',
        'prix_achat_unitaire',
    ];

    protected $casts = [
        'date_expiration' => 'date',
    ];

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(Mouvement_Stock::class);
    }

    // Validation pour garantir que quantite_actuelle >= 0
    protected static function booted(): void
    {
        static::saving(function ($lot) {
            if ($lot->quantite_actuelle < 0) {
                throw new \InvalidArgumentException('La quantité actuelle ne peut pas être négative.');
            }
        });
    }
}
