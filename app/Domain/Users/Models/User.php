<?php

namespace App\Domain\Users\Models;

use App\Domain\SolarSystem\Models\Message;
use App\Domain\Users\Actions\DeletePersonalData;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    public $fillable = [
        'name',
        'email',
        'rank',
        'origin',
        'duties',
        'position',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $casts = [
        'duties' => 'array',
        'email_verified_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleted(
            function(User $user) {
                resolve(DeletePersonalData::class, ['user' => $user])->execute();
            }
        );

        static::creating(
            function(User $user) {
                $user->generateUUID();
            }
        );
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id', 'id');
    }

    /**
     * @return $this
     *
     * @throws \Exception
     */
    private function generateUUID(): self
    {
        if (is_null($this->uuid)) {
            do {
                $this->uuid = $this->getUUID();
            } while (self::where('uuid', $this->uuid)->exists());
        }

        return $this;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    private function getUUID(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
