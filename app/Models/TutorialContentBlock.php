<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TutorialContentBlock extends Model
{
    use SoftDeletes;

    public const TYPE_TEXT = 'text';
    public const TYPE_IMAGE = 'image';
    public const TYPE_YOUTUBE = 'youtube';
    public const TYPE_PDF = 'pdf';

    public const TYPES = [
        self::TYPE_TEXT,
        self::TYPE_IMAGE,
        self::TYPE_YOUTUBE,
        self::TYPE_PDF,
    ];

    protected $fillable = [
        'tutorial_node_id',
        'block_type',
        'title',
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
            'tutorial_node_id' => 'integer',
            'file_size' => 'integer',
            'sort_order' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function tutorialNode(): BelongsTo
    {
        return $this->belongsTo(
            TutorialNode::class
        );
    }

    public function isText(): bool
    {
        return $this->block_type ===
            self::TYPE_TEXT;
    }

    public function isImage(): bool
    {
        return $this->block_type ===
            self::TYPE_IMAGE;
    }

    public function isYoutube(): bool
    {
        return $this->block_type ===
            self::TYPE_YOUTUBE;
    }

    public function isPdf(): bool
    {
        return $this->block_type ===
            self::TYPE_PDF;
    }
}