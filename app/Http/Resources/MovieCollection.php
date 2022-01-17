<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieCollection extends ResourceCollection
{
    protected int $person_id;

    public function person(int $person_id)
    {
        $this->person_id = $person_id;

        return $this;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function (MovieResource $resource) use ($request) {
            return $resource->person($this->person_id)->toArray($request);
        })->all();
    }
}
