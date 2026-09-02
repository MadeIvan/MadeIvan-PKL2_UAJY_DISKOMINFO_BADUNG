<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicationVersion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'application_id',
        'version_number',
        'release_date',
        'release_notes',
        'status',
        'is_current',
    ];

    protected $casts = [
        'release_date' => 'date',
        'is_current' => 'boolean',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_BETA = 'beta';
    public const STATUS_STABLE = 'stable';
    public const STATUS_DEPRECATED = 'deprecated';
    public const STATUS_PRIVAT = 'privat';

    /**
     * Semua status yang dapat dipilih.
     */
    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_BETA,
        self::STATUS_STABLE,
        self::STATUS_DEPRECATED,
        self::STATUS_PRIVAT,
    ];

    /**
     * Status yang boleh dilihat pengunjung anonim.
     */
    public const PUBLIC_STATUSES = [
        self::STATUS_BETA,
        self::STATUS_STABLE,
        self::STATUS_DEPRECATED,
    ];

    /**
     * Status yang boleh dilihat pengguna internal (Admin/Pegawai),
     * tetapi tidak terlihat oleh pengunjung anonim.
     */
    public const INTERNAL_STATUSES = [
        self::STATUS_BETA,
        self::STATUS_STABLE,
        self::STATUS_DEPRECATED,
        self::STATUS_PRIVAT,
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
    public function tutorialNodes(): HasMany
    {
        return $this
            ->hasMany(TutorialNode::class)
            ->orderBy('sort_order')
            ->orderBy('title');
    }

    /**
     * Versions that may be shown on the public side.
     *
     * Draft versions are excluded; beta, stable, and deprecated
     * versions are still considered publicly visible.
     */
    public function scopeVisibleToPublic(Builder $query): Builder
    {
        return $query->whereIn(
            'status',
            self::PUBLIC_STATUSES
        );
    }

    /**
     * Versions that may be shown to internal users (Admin/Pegawai).
     *
     * This includes everything public plus the "privat" status,
     * while still excluding drafts (Admin-only).
     */
    public function scopeVisibleToInternal(Builder $query): Builder
    {
        return $query->whereIn(
            'status',
            self::INTERNAL_STATUSES
        );
    }
}