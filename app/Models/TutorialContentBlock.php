<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class TutorialContentBlock extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tutorial_node_id',
        'block_type',
        'content',
        'file_path',
        'original_file_name',
        'file_size',
        'mime_type',
        'external_url',
        'caption',
        'alt_text',
        'sort_order',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'sort_order' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function tutorialNode(): BelongsTo
    {
        return $this->belongsTo(TutorialNode::class);
    }
}