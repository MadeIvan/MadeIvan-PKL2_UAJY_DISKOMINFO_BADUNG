<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class TutorialNode extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    public const TYPE_KATEGORI = 'kategori';
    public const TYPE_BAGIAN = 'bagian';
    public const TYPE_MATERI = 'materi';

    public const TYPES = [
        self::TYPE_KATEGORI,
        self::TYPE_BAGIAN,
        self::TYPE_MATERI,
    ];

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
        return $this->belongsTo(
            Application::class
        );
    }

    public function applicationVersion(): BelongsTo
    {
        return $this->belongsTo(
            ApplicationVersion::class
        );
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

    public function contentBlocks(): HasMany
    {
        return $this
            ->hasMany(
                TutorialContentBlock::class
            )
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function scopeRoots(
        Builder $query
    ): Builder {
        return $query->whereNull(
            'parent_id'
        );
    }

    public function scopePublished(
        Builder $query
    ): Builder {
        return $query
            ->where(
                'status',
                'published'
            )
            ->where(
                'is_public',
                true
            );
    }

    public function scopeOrdered(
        Builder $query
    ): Builder {
        return $query
            ->orderBy('sort_order')
            ->orderBy('title');
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    public function isKategori(): bool
    {
        return $this->node_type ===
            self::TYPE_KATEGORI;
    }

    public function isBagian(): bool
    {
        return $this->node_type ===
            self::TYPE_BAGIAN;
    }

    public function isMateri(): bool
    {
        return $this->node_type ===
            self::TYPE_MATERI;
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}