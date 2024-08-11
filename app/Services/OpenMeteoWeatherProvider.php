<?php

namespace App\Services;

use App\Interfaces\WeatherProviderInterface;

class OpenMeteoWeatherProvider implements WeatherProviderInterface
{
    public function getData(float $long, float $lat)
    {
        // get from API
    }
}