<?php

namespace Pkalusek\LaravelModelSettings\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Pkalusek\LaravelModelSettings\Models\Setting;
use Pkalusek\LaravelModelSettings\Tests\TestCase;
use Pkalusek\LaravelModelSettings\Traits\HasSettings;

class HasSettingsTest extends TestCase
{
    private TestModel $model;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->model = TestModel::create(['name' => 'Test Model']);
    }

    public function test_can_set_and_get_setting()
    {
        $this->model->settings->set('theme', 'dark');
        
        $this->assertEquals('dark', $this->model->settings->get('theme'));
    }

    public function test_can_set_and_get_nested_setting()
    {
        $this->model->settings->set('notifications.email', true);
        
        $this->assertTrue($this->model->settings->get('notifications.email'));
    }

    public function test_returns_default_value_when_setting_not_found()
    {
        $this->assertEquals('light', $this->model->settings->get('theme', 'light'));
        $this->assertNull($this->model->settings->get('nonexistent'));
    }

    public function test_can_forget_setting()
    {
        $this->model->settings->set('theme', 'dark');
        $this->assertEquals('dark', $this->model->settings->get('theme'));
        
        $this->model->settings->forget('theme');
        $this->assertNull($this->model->settings->get('theme'));
    }

    public function test_can_forget_nested_setting()
    {
        $this->model->settings->set('notifications.email', true);
        $this->model->settings->set('notifications.sms', false);
        
        $this->model->settings->forget('notifications.email');
        
        $this->assertNull($this->model->settings->get('notifications.email'));
        $this->assertFalse($this->model->settings->get('notifications.sms'));
    }

    public function test_setting_record_is_created_in_database()
    {
        $this->model->settings->set('theme', 'dark');
        
        $this->assertDatabaseHas('settings', [
            'model_type' => TestModel::class,
            'model_id' => $this->model->id,
        ]);
    }

    public function test_setting_record_is_deleted_when_all_settings_removed()
    {
        $this->model->settings->set('theme', 'dark');
        
        $settingRecord = Setting::where('model_type', TestModel::class)
            ->where('model_id', $this->model->id)
            ->first();
        
        $this->assertNotNull($settingRecord);
        $settingId = $settingRecord->id;
        
        $this->model->settings->forget('theme');
        
        $this->assertNull(Setting::find($settingId));
    }
}

class TestModel extends Model
{
    use HasSettings;

    protected $table = 'test_models';
    protected $fillable = ['name'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!app()->runningInConsole() || app()->runningUnitTests()) {
                \Illuminate\Support\Facades\Schema::create('test_models', function ($table) {
                    $table->id();
                    $table->string('name');
                    $table->timestamps();
                });
            }
        });
    }
}