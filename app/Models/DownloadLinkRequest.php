<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class DownloadLinkRequest extends Model
{
    protected static ?bool $tableExists = null;

    protected $fillable = [
        'phone',
        'country_code',
        'destination',
        'ip_address',
        'user_agent',
    ];

    public static function isAvailable(): bool
    {
        return static::$tableExists ??= Schema::hasTable((new static)->getTable());
    }

    public static function totalCount(): int
    {
        return static::isAvailable() ? static::query()->count() : 0;
    }
}
