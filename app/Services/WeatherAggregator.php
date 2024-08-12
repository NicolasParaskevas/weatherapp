<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
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
            
            // insert the average in db
            try
            {
                DB::beginTransaction();
                $record = new WeatherRecord();
                $record->location_id = $location->id;
                $record->temperature = $avg;
                $record->created_at  = date("Y-m-d $hour:i:s");
                $record->updated_at  = date("Y-m-d $hour:i:s");
                $record->save();
                DB::commit();

            }
            catch (\Exception $e)
            {
                Log::error("Error when saving weather_record for location: " . $location->id . " - " . $e->getMessage());
            }
        }
    }
}