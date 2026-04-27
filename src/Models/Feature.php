<?php

namespace Imran\LaravelRuntimeFeature\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
{
    protected $fillable = [
        'key',
        'enabled',
        'value',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'value' => 'json',
    ];

    public function rules(): HasMany
    {
        return $this->hasMany(FeatureRule::class);
    }
}
