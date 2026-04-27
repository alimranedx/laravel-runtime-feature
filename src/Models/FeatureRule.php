<?php

namespace Imran\LaravelRuntimeFeature\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureRule extends Model
{
    protected $fillable = [
        'feature_id',
        'type',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }
}
