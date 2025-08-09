<?php

namespace Pkalusek\LaravelModelSettings\Utils;

use Illuminate\Database\Eloquent\Model;
use Pkalusek\LaravelModelSettings\Models\Setting;

class SettingsProxy
{
    private ?Setting $settings = null;

    public function __construct(
        private Model $model
    ) {
        $this->settings = Setting::query()
            ->where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->first();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if (empty($this->settings)) {
            return $default;
        }
        return data_get($this->settings->value, $key, $default);
    }

    public function set(string $key, mixed $value): self
    {
        if (empty($this->settings)) {
            $this->settings = Setting::create([
                'value' => [],
                'model_type' => get_class($this->model),
                'model_id' => $this->model->id,
            ]);
        }

        $settings = $this->settings->value;
        $this->settings->update([
            'value' => data_set($settings, $key, $value),
        ]);
        return $this;
    }

    public function forget(string $key): self
    {
        if (empty($this->settings)) {
            return $this;
        }

        $settings = $this->settings->value;
        data_forget($settings, $key);

        # If nested keys are also empty, remove them
        foreach (explode('.', $key) as $segment) {
            if (empty($settings[$segment])) {
                unset($settings[$segment]);
            }
        }

        if (empty($settings)) {
            $this->settings->delete();
            $this->settings = null;
            return $this;
        }
        $this->settings->update([
            'value' => $settings,
        ]);
        return $this;
    }
}
