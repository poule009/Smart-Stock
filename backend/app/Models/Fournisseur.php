<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fournisseur extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nom', 'email', 'telephone', 'adresse'];

    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }
}
