<?php

namespace Pkalusek\LaravelModelSettings\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Pkalusek\LaravelModelSettings\Utils\SettingsProxy;

trait HasSettings
{
    public function settings(): Attribute
    {
        return new Attribute(
            get: fn() => new SettingsProxy($this),
        );
    }
}
