<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Location;
use App\Models\WeatherRecord;

class WeatherAggregator
{
    private $weatherProviders;

    public function __construct(array $wp)
    {
        $this->weatherProviders = $wp;
    }

    public function aggregateData(Location $location, int $hour)
    {
        $temps = [];

        foreach($this->weatherProviders as $wp) 
        {
            $data = $wp->getData($location->longitude, $location->latitude, $hour);
            if ($data === false)
            {
                continue;
            }

            $temps[] = $data["temp"];
        }

        if (count($temps) > 0)
        {
            $avg = array_sum($temps)/count($temps);
            try
            {
                DB::transaction(function () use ($location, $avg, $hour) {
                    $record = new WeatherRecord();
                    $record->location_id = $location->id;
                    $record->temperature = $avg;
                    $record->created_at  = date("Y-m-d $hour:00:00");
                    $record->updated_at  = date("Y-m-d $hour:00:00");
                    $record->save();
                });
            }
            catch(\Exception $e)
            {
                Log::error("Error when saving weather_record for location: " . $location->id . " - " . $e->getMessage());
            }   
        }
    }
}