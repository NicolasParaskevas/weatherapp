<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class WeatherService
{
    public function getTemperature(float $long, float $lat, string $time)
    {
        // get from DB
        $record = DB::table("weather_records")
        ->join("locations", "locations.id", "=", "weather_records.location_id")
        ->where("locations.longitude", "=", $long)
        ->where("locations.latitude", "=", $lat)
        ->where("weather_records.created_at", "=", $time)
        ->pluck("weather_records.temperature")
        ->first();

        return $record;
    }
}