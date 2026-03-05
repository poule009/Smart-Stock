<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'quantite_initiale'   => $this->quantite_initiale,
            'quantite_actuelle'   => $this->quantite_actuelle,
            'date_expiration'     => $this->date_expiration?->format('Y-m-d'),
            'prix_achat_unitaire' => $this->prix_achat_unitaire,
            'est_expire'          => $this->date_expiration?->isPast() ?? false,

            'produit'             => new ProduitResource($this->whenLoaded('produit')),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
