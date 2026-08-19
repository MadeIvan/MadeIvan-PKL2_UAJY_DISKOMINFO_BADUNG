<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Application extends Model
{
    use SoftDeletes;
    use Searchable;

protected $fillable = [
    'name',
    'slug',
    'description',
    'category_id',
    'logo_path',
    'cover_path',
    'status',
    'is_public',
];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    public function versions(): HasMany
{
    return $this->hasMany(ApplicationVersion::class);
}

    public function currentVersion(): HasOne
    {
        return $this->hasOne(ApplicationVersion::class)
            ->where('is_current', true);
    }

public function category(): BelongsTo
{
    return $this->belongsTo(Category::class)->withTrashed();
}

    public function tutorialNodes(): HasMany
    {
        return $this
            ->hasMany(TutorialNode::class)
            ->orderBy('sort_order')
            ->orderBy('title');
    }
    public function rootTutorialNodes(): HasMany
    {
        return $this
            ->hasMany(TutorialNode::class)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('title');
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
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}