<?php

namespace App\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ArtistDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'songs' => SongResource::collection($this->whenLoaded('songs')),
            'albums' => AlbumResource::collection($this->whenLoaded('albums')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
