<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'avatar' => $this->avatar,
            'songs' => SongResource::collection($this->whenLoaded('songs')),
            'albums' => AlbumResource::collection($this->whenLoaded('albums')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
