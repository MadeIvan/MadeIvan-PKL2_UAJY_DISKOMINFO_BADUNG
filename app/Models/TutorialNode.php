<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TutorialNode extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'application_id',
        'application_version_id',
        'parent_id',
        'title',
        'slug',
        'description',
        'node_type',
        'sort_order',
        'status',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'application_id' => 'integer',
            'application_version_id' => 'integer',
            'parent_id' => 'integer',
            'sort_order' => 'integer',
            'is_public' => 'boolean',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function applicationVersion(): BelongsTo
    {
        return $this->belongsTo(ApplicationVersion::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            TutorialNode::class,
            'parent_id'
        );
    }

    public function children(): HasMany
    {
        return $this
            ->hasMany(
                TutorialNode::class,
                'parent_id'
            )
            ->orderBy('sort_order')
            ->orderBy('title');
    }

    public function childrenRecursive(): HasMany
    {
        return $this
            ->children()
            ->with('childrenRecursive');
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where('is_public', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('sort_order')
            ->orderBy('title');
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }
    
}