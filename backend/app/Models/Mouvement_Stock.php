<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mouvement_Stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mouvements_stock';

    protected $fillable = [
        'lot_id',
        'user_id',
        'type',
        'quantite',
        'raison',
    ];

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
