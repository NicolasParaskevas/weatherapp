<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class WeatherAggregator
{
    private $weatherProviders;

    public function __construct(array $wp)
    {
        $this->weatherProviders = $wp;
    }

    public function aggregateData(float $long, float $lat, int $hour)
    {
        $temps = [];

        foreach($this->weatherProviders as $wp) 
        {
            $data = $wp->getData($long, $lat, $hour);
            if ($data === false)
            {
                // log error and continue

                continue;
            }
        }

        if (count($temps) > 0)
        {
            $avg = array_sum($temps)/count($temps);
            // insert the average in db

        }
    }
}