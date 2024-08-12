<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    public function getTemperature(float $long, float $lat, string $time)
    {
        $response = null;

        $dateFrom = date("Y-m-d H:00:00", strtotime($time));
        $dateTo   = date("Y-m-d H:59:59", strtotime($time));

        // get from DB
        // For some reason I couldn't get this to work
        // so I'm writing it as a parametarized sql query
        // $record = DB::table("weather_records")
        // ->join("locations", "locations.id", "=", "weather_records.location_id")
        // ->where("locations.longitude", $long)
        // ->where("locations.latitude", $lat)
        // ->whereDate("weather_records.created_at", ">=", $dateFrom)
        // ->whereDate("weather_records.created_at", "<=", $dateTo)
        // ->pluck("weather_records.temperature")
        // ->first();

        $record = DB::select(
                "select wr.temperature from weather_records as wr
                inner join locations as l on l.id = wr.location_id
                where l.longitude = :long and l.latitude = :lat and
                wr.created_at >= :dateFrom and wr.created_at <= :dateTo limit 1"
            ,
            [
                'lat'      => $lat,
                'long'     => $long,
                'dateFrom' => $dateFrom,
                'dateTo'   => $dateTo
            ]
        );

        // log error if not found
        if (!isset($record[0]))
        {
            Log::error("Error could not get temperature for location: long: " . 
                $long . " lat: " . $lat . " and time: " . $time
            );
        }
        else
        {
            $response = $record[0]->temperature;
        }
        
        return $response;
    }
}