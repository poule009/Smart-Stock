<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MouvementStockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'type'     => $this->type,
            'quantite' => $this->quantite,
            'raison'   => $this->raison,

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),

            'lot'      => new LotResource($this->whenLoaded('lot')),
            'user'     => $this->whenLoaded('user', function () {
                return [
                    'id'  => $this->user->id,
                    'nom' => $this->user->nom,
                ];
            }),
        ];
    }
}
