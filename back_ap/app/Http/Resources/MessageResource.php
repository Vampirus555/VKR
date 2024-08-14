<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'dialog_id' => $this->dialog_id,
            'user_fullName' => $this->user()->getFullNameAttribute(),
            'text' => $this->text,
            'data_time' => $this->created_at,
        ];
    }
}