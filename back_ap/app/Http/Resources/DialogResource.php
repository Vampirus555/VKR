<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DialogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->event()->pluck('name'),
            'organizer' => new OrganizerResource($this->organizer_id),
            'delegate' => new DelegateResource($this->delegate_id),
        ];
    }
}