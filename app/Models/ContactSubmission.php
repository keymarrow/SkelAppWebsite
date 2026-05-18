<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ContactSubmission extends Model
{
    protected static ?bool $tableExists = null;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'ip_address',
        'user_agent',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function fullName(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function markRead(): void
    {
        if (! $this->read_at) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

    public static function isAvailable(): bool
    {
        return static::$tableExists ??= Schema::hasTable((new static)->getTable());
    }

    public static function unreadCount(): int
    {
        return static::isAvailable()
            ? static::query()->whereNull('read_at')->count()
            : 0;
    }
}
