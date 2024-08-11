<?php

namespace App\Services;

use App\Interfaces\WeatherProviderInterface;

class WeatherApiWeatherProvider implements WeatherProviderInterface
{
    public function getData(float $long, float $lat)
    {
        // get from API
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.open-meteo.com/v1/forecast?latitude='.$long.'&longitude='.$lat.'&hourly=temperature_2m&forecast_days=1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch))
        {
            // TODO log error getting data from API
            // echo 'Error:' . curl_error($ch);
            // curl_close($ch);
            // return;
        }
        
        curl_close($ch);

        $result_json = json_decode($result, true);

        if (!is_array($result_json))
        {
            // TODO log error parsing json response 
            // return
        }

        if (isset($result_json["error"]) && $result_json["error"] === true)
        {
            // TODO log error $result_json["reason"]
            // return

        }



    }
}