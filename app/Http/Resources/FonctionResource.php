<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FonctionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title'   => (string) $this->type_id,
            'content' => [
                'id'      => (string) $this->type_id,
                'libelle' => $this->name,
            ],
        ];
    }
}
