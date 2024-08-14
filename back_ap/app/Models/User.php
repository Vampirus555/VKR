<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'surname',
        'name',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'img_path',
    ];

    public function getFullNameAttribute(): string
    {
        return $this->attributes['name'] . ' ' . $this->attributes['surname'];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function organizer(): HasOne
    {
        return $this->hasOne(Organizer::class);
    }

    public function delegate(): HasOne
    {
        return $this->hasOne(Delegate::class);
    }

    public function notes(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }

    public function messages(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function reviews(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }


}
