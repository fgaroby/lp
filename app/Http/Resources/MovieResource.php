<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title'   => $this->product_title,
            'content' => [
                'brightcove_id'    => (string) $this->brightcove_id,
                'product_title'    => $this->product_title,
                'age_limit'        => $this->age_limit,
                'description'      => $this->description,
                'movie_duration'   => (string) $this->movie_duration,
                'imbd_id'          => $this->imdb_id,
                'original_title'   => $this->original_title,
                'premiere'         => $this->premiere,
                'production_year'  => (string) $this->production_year,
                'search_engine'    => $this->search_engine,
                'official_website' => $this->official_website,
                'pictures'         => PictureResource::collection($this->pictures),
                //'fonctions'        => FonctionResource::collection($this->fonctions),
            ],
        ];
    }
}
