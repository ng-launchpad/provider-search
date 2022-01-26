<?php

namespace Tests\Unit\Setting;

use App\Models\Setting;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VersionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_gets_the_version()
    {
        self::assertEquals(0, Setting::version());
    }

    /** @test */
    public function it_gets_the_next_version()
    {
        self::assertEquals(1, Setting::nextVersion());
    }

    /** @test */
    public function it_bumps_the_version()
    {
        self::assertEquals(0, Setting::version());
        self::assertEquals(1, Setting::bumpVersion());
        self::assertEquals(1, Setting::version());
    }

    /** @test */
    public function it_sets_a_new_setting()
    {
        // arrange
        $key   = 'test';
        $value = ['foo', 'bar'];

        // act
        $setting = Setting::set($key, $value);

        // assert
        self::assertInstanceOf(Setting::class, $setting);
        self::assertEquals($value, json_decode($setting->value));
    }

    /** @test */
    public function it_sets_an_existing_setting()
    {
        // arrange
        $key   = Setting::KEY_VERSION;
        $value = ['foo', 'bar'];

        // act
        $setting = Setting::set($key, $value);

        // assert
        self::assertInstanceOf(Setting::class, $setting);
        self::assertEquals($value, json_decode($setting->value));
    }

    /** @test */
    public function it_gets_a_setting_value()
    {
        // arrange
        $key = Setting::KEY_VERSION;

        // act
        $setting = Setting::get($key);

        // assert
        self::assertEquals(0, $setting);
    }

    /** @test */
    public function it_gets_a_setting_object()
    {
        // arrange
        $key = Setting::KEY_VERSION;

        // act
        $setting = Setting::getSetting($key);

        // assert
        self::assertInstanceOf(Setting::class, $setting);
    }

    /** @test */
    public function it_throws_exception_for_missing_setting()
    {
        // arrange
        $key = 'missing';

        // assert
        self::expectException(ModelNotFoundException::class);

        // act
        Setting::get($key);
    }

    /** @test */
    public function it_unsets_a_setting()
    {
        // arrange
        $key   = 'foo';
        $value = 'bar';

        Setting::set($key, $value);
        self::assertEquals($value, Setting::get($key));

        // act
        Setting::unset($key);

        // assert
        self::expectException(ModelNotFoundException::class);
        Setting::get($key);
    }
}
