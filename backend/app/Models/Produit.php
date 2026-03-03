<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'categorie_id',
        'fournisseur_id',
        'nom',
        'unite',
        'seuil_alerte',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function lots()
    {
        return $this->hasMany(Lot::class);
    }
}
