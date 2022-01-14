<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
            'id'       => $this->id_kwm_lp,
            'idPerson' => $this->person_id,
            'type'     => 'info-cine',
            'title'    => $this->name_person,
            'content'  => [
                'nom'            => $this->name_person,
                'url_dbpedia'    => $this->url_dbpedia,
                'lieu_naissance' => $this->lieu_naissance,
                'nationalite'    => $this->nationalite,
                'commentaire'    => $this->commentaire,
                'profession'     => $this->profession,
                'date_naissance' => optional($this->date_naissance)->format('Y'),
                'photo'          => $this->photo,
                'movies'         => MovieResource::collection($this->movies->keyBy->movie_id),
            ],
        ];
    }
}
