<?php

namespace Imran\RuntimeFeatureToggle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
{
    protected $table = 'rtf_features';

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
