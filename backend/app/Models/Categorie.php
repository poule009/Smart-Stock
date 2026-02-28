<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model
{
    use SoftDeletes;

    protected $fillable = ['nom'];

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
