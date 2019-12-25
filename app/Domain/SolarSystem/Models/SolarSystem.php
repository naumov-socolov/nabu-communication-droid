<?php

namespace App\Domain\SolarSystem\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolarSystem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'region',
        'sector',
        'suns',
        'grid_coordinates',
        'trade_routes',
        'img',
    ];

    protected $casts = [
        'suns' => 'array',
        'trade_routes' => 'array',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'solar_system_id', 'id');
    }
}
