<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProduitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'nom'           => $this->nom,
            'unite'         => $this->unite,
            'seuil_alerte'  => $this->seuil_alerte,

            // Relations (seulement si chargées)
            'categorie'     => new CategorieResource($this->whenLoaded('categorie')),
            'fournisseur'   => new FournisseurResource($this->whenLoaded('fournisseur')),

            'nombre_lots'   => $this->whenLoaded('lots', function () {
                return $this->lots->count();
            }),

            'stock_total'   => $this->whenLoaded('lots', function () {
                return $this->lots->sum('quantite_actuelle');
            }),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
