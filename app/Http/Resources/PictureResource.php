<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PictureResource extends JsonResource
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
            'title'   => (string) $this->picture_id,
            'content' => [
                'name'         => $this->name,
                'url'          => $this->url,
                'width'        => (string) $this->width,
                'height'       => (string) $this->height,
                'mime_type'    => $this->mime_type,
                'picture_type' => (string) $this->picture_type_picture_type_id,
            ],
        ];
    }
}
