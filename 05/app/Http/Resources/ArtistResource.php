<?php

namespace App\Http\Resources;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->{Artist::COLUMN_ID},
            'user_id' => $this->{Artist::COLUMN_USER_ID},
            'name' => $this->{Artist::COLUMN_NAME},
            'no_of_tabs' => $this->{Artist::COLUMN_NO_OF_TABS},
            'no_of_views' => $this->{Artist::COLUMN_NO_OF_VIEWS},
        ];
    }
}
