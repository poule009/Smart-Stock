<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mouvement_Stock extends Model
{
    protected $table = 'mouvements_stock';

    protected $fillable = [
        'lot_id',
        'user_id',
        'type',
        'quantite',
        'raison',
        'cree_le',
    ];

    protected $casts = [
        'cree_le' => 'datetime',
    ];

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
