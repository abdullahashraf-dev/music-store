<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SongResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'duration' => $this->duration,
            'album_id' => $this->album_id,
            'album' => new AlbumResource($this->whenLoaded('album')),
            'artists' => ArtistResource::collection($this->whenLoaded('artists')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
