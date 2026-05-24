<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class VisitorEvent extends Model
{
    protected static ?bool $tableExists = null;

    public $timestamps = false;

    protected $fillable = [
        'session_id',
        'path',
        'host',
        'referer',
        'referrer_host',
        'source',
        'user_agent_hash',
        'ip_hash',
        'visitor_id_hash',
        'is_new_session',
        'is_new_visitor',
        'session_event_index',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'is_new_session' => 'boolean',
            'is_new_visitor' => 'boolean',
            'session_event_index' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function scopeBetween(Builder $query, Carbon $from, Carbon $to): Builder
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function scopeNewSessions(Builder $query): Builder
    {
        return $query->where('is_new_session', true);
    }

    /**
     * Restrict to events captured on the public marketing site, excluding the
     * admin domain. Falls back to legacy rows (no host recorded) when the
     * public host isn't configured.
     */
    public function scopePublicSite(Builder $query): Builder
    {
        $publicHost = (string) config('cms.public_host');
        $adminHost = (string) config('cms.admin_host');

        if ($publicHost !== '') {
            return $query->where(function (Builder $q) use ($publicHost) {
                $q->where('host', $publicHost)->orWhereNull('host');
            });
        }

        if ($adminHost !== '') {
            return $query->where(function (Builder $q) use ($adminHost) {
                $q->where('host', '!=', $adminHost)->orWhereNull('host');
            });
        }

        return $query;
    }

    public static function isAvailable(): bool
    {
        return static::$tableExists ??= Schema::hasTable((new static)->getTable());
    }
}
