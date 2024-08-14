<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    //назначение свойств

    protected $fillable = [
        'id',
        'name',
        'type',
        'park_id',
        'organizer_id',
        'categories',
        'phone',
        'status',
        'limit_age', 
        'start_date' , 
        'end_date' , 
        'schedule' ,
        'img_path' , 
        'desc' ,  

    ];
    protected $allowedFilters = [
        'id',
        'name',
        'status',
        'type',
        'start_data',
        'end_data',
        'park_id',
        'categories',
        'schedule',
        'limit_age',
        'price',
        'phone'

    ];

    //обозначение связей

    public function park(): BelongsTo
    {
        return $this->belongsTo(Park::class);
    }

    public function organizer(): BelongsTo 
    {
        return $this->belongsTo(Organizer::class);
    }

    public function views(): HasMany 
    {
        return $this->hasMany(View::class);
    }
    
    public function reviews(): HasMany 
    {
        return $this->hasMany(Review::class);
    }

    public function notes(): HasMany 
    {
        return $this->hasMany(Note::class);
    }
    

    public function categories(): BelongsToMany 
    {
        return $this->belongsToMany(Category::class, 'event_categories')->using(EventCategory::class);
    }
}
