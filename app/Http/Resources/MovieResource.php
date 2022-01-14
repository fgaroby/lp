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

    protected int $person_id;

    public function person(int $person_id)
    {
        $this->person_id = $person_id;

        return $this;
    }

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
                'fonctions'        => FonctionResource::collection($this->fonctions($this->person_id)),
            ],
        ];
    }

    public static function collection($resource)
    {
        return tap(new MovieCollection($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }
}
