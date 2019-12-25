<?php

namespace App\Domain\SolarSystem\Models;

use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    const APPROVED = "APPROVED";
    const CANCELLED = "CANCELLED";
    const DATA_ERROR = "DATA_ERROR";
    const SERVER_ERROR = "SERVER_ERROR";
    const WAITING = "WAITING";
    const WAITING_QUEUE = "WAITING_QUEUE";
    const PENDING = "PENDING";
    const ISSUED = "ISSUED";
    const REJECTED = "REJECTED";

    const STATUS_TEXTS = [
        self::APPROVED => "approved",
        self::CANCELLED => "cancelled",
        self::DATA_ERROR => "error",
        self::SERVER_ERROR => "error",
        self::WAITING => "waiting",
        self::WAITING_QUEUE => "waiting",
        self::PENDING => "pending",
        self::ISSUED => "issued",
        self::REJECTED => "rejected",
    ];

    protected $fillable = [
        'user_id',
        'solar_system_id',
        'status',
        'amount',
        'hash_key',
        'partner_request_id',
    ];

    protected $visible = [
        'id',
        'status',
        'status_label',
        'hash_key',
        'updated_at',
        'expired_at',
        'amount',
    ];

    protected $appends = ['status_label'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_TEXTS[$this->status];
    }

    public function requestLogs(): HasMany
    {
        return $this->hasMany(RequestLog::class);
    }

    public function solarSystem(): HasOne
    {
        return $this->hasOne(SolarSystem::class, 'id', 'solar_system_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
