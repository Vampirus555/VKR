<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Park extends Model
{
    use HasFactory;

    protected $fillable = [
        'desc',
        'work_time',
        'website',
        'phone',
        'img_path',
    ];

    public function metrostations(): BelongsToMany
    {
        return $this->belongsToMany(Metrostation::class, 'park_metrostations')->using(ParkMetrostation::class);
    }
}
