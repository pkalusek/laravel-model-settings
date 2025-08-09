<?php

namespace Pkalusek\LaravelModelSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Setting extends Model
{
    protected $fillable = ['value', 'model_type', 'model_id'];

    protected $casts = ['value' => 'array'];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
