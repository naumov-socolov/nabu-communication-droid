<?php

namespace App\Domain\SolarSystem\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestLog extends Model
{
    use SoftDeletes;

    private $decodedResponse;

    protected $fillable = [
        'message_id',
        'request_name',
        'request_data',
        'response_data',
        'status',
    ];

    protected $appends = [
        'response_status_code',
        'response_exception_message',
        'response_body',
    ];

    public function getResponseDataArrayAttribute(): array
    {
        if ($this->decodedResponse === null) {
            $this->decodedResponse = json_decode($this->response_data, true);
        }

        return $this->decodedResponse;
    }

    public function getResponseStatusCodeAttribute(): ?string
    {
        return $this->response_data_array['status_code'] ?? null;
    }

    public function getResponseExceptionMessageAttribute(): ?string
    {
        return $this->response_data_array['exception_message'] ?? null;
    }

    public function getResponseBodyAttribute(): ?string
    {
        return $this->response_data_array['body'] ?? null;
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id', 'id');
    }
}
