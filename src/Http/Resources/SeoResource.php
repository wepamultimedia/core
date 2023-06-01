<?php

namespace Wepa\Core\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class SeoResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'image' => $this->image,
            'image_alt' => $this->image_alt
        ];
    }
}
