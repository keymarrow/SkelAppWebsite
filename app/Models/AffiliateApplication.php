<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AffiliateApplication extends Model
{
    protected static ?bool $tableExists = null;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_country_code',
        'phone_number',
        'country',
        'primary_promotional_method',
        'hear_about_program',
        'marketing_details',
        'accepts_agreement',
        'accepts_marketing',
        'eligibility_confirmed',
        'ip_address',
        'user_agent',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'accepts_agreement' => 'boolean',
            'accepts_marketing' => 'boolean',
            'eligibility_confirmed' => 'boolean',
            'reviewed_at' => 'datetime',
        ];
    }

    public function fullName(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function phoneDisplay(): string
    {
        return trim($this->phone_country_code.' '.$this->phone_number);
    }

    public function markReviewed(): void
    {
        if (! $this->reviewed_at) {
            $this->forceFill(['reviewed_at' => now()])->save();
        }
    }

    public static function isAvailable(): bool
    {
        return static::$tableExists ??= Schema::hasTable((new static)->getTable());
    }

    public static function pendingCount(): int
    {
        return static::isAvailable()
            ? static::query()->whereNull('reviewed_at')->count()
            : 0;
    }
}
