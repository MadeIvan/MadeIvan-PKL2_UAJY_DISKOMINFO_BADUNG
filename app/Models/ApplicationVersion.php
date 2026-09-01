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
            [
                'beta',
                'stable',
                'deprecated',
            ]
        );
    }
}