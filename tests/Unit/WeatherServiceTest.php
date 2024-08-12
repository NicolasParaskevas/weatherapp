<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\WeatherService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WeatherServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testGetTemperatureReturnsTemperatureWhenRecordExists()
    {
        $longitude = 12.34;
        $latitude = 56.78;
        $time = '2024-08-12 00:00:00';
        $expectedTemperature = 25.5;

        DB::shouldReceive('table->join->where->where->where->pluck->first')
        ->once()
        ->andReturn($expectedTemperature);

        $weatherService = new WeatherService();

        $result = $weatherService->getTemperature($longitude, $latitude, $time);

        $this->assertEquals($expectedTemperature, $result);
    }

    public function testGetTemperatureReturnsNullAndLogsErrorWhenRecordDoesNotExist()
    {
        $longitude = 12.34;
        $latitude = 56.78;
        $time = '2024-08-12 00:00:00';

        // Mock DB to return null
        DB::shouldReceive('table->join->where->where->where->pluck->first')
        ->once()
        ->andReturn(null);

        Log::shouldReceive('error')
        ->once()
        ->withArgs(function($message) use ($longitude, $latitude, $time) {
            return strpos($message, "long: $longitude") !== false &&
                    strpos($message, "lat: $latitude") !== false &&
                    strpos($message, "time: $time") !== false;
        });

        $weatherService = new WeatherService();

        $result = $weatherService->getTemperature($longitude, $latitude, $time);

        $this->assertNull($result);
    }
}
