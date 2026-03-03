<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fournisseur extends Model
{
    use SoftDeletes;

    protected $fillable = ['nom', 'email', 'telephone', 'adresse'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function lots()
    {
        return $this->hasMany(Lot::class);
    }
}
