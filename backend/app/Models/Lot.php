<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    protected $fillable = [
        'produit_id',
        'quantite_initiale',
        'quantite_actuelle',
        'date_expiration',
        'prix_achat_unitaire',
        'cree_le',
    ];

    protected $casts = [
        'date_expiration' => 'date',
        'cree_le' => 'datetime',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(Mouvement_Stock::class);
    }

        // Validation pour garantir que quantite_actuelle >= 0
        public static function boot()
        {
            parent::boot();
            static::saving(function ($lot) {
                if ($lot->quantite_actuelle < 0) {
                    throw new \InvalidArgumentException('La quantité actuelle ne peut pas être négative.');
                }
            });
        }
}
