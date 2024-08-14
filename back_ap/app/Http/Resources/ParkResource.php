<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $path = ($this->img_path) ? $this->img_path : 'img/blank.jpg'; // формирвоание полного пути к изображению
        return [
            'id' => $this->id,
            'name' => $this->name,
            'desc' => $this->desc,
            'short_desc' => $this->short_desc,
            'address' => $this->address,
            'geolocation' => $this->geolocation,
            'work_time' => $this->work_time,
            'website' => $this->website,
            'phone' => $this->phone,
            'metrostations' => $this->metrostations->pluck('station'), // подгружаем связанные с парком станции метро
            'image' => asset('storage/' . $path),
        ];
    }
}