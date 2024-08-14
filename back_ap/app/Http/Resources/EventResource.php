<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        $path = ($this->img_path) ? $this->img_path : 'img/event.jpg';
        // $additionalData = $this->resource->additional;
        return [
            'id' => $this->id,
            'park' => $this->park()->pluck('name'),
            'organizer' => $this->organizer()->pluck('organization'),
            'categories' =>  $this->categories->pluck('name'),
            'name' => $this->name,
            'status' => $this->status,
            'type' => $this->type,
            'limit_age' => $this->limit_age,
            'desc' => $this->desc,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'schedule' => $this->schedule,
            'price' => $this->price,
            'phone' => $this->phone,
            'image' => asset('storage/' . $path),
            // 'views_count' => isset($additionalData['viewsCount']) ? $additionalData['viewsCount'] : 0,
            // 'average_rating' => $additionalData['averageRating'] ?? 0,
            'views_count' => $this->views_count ?? 0,
            'average_rating' => $this->average_rating ?? 0,
            'hasNote' => $this->hasNote,
        ];
    }
}