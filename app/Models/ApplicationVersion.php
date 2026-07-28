<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}