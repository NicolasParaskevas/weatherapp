<?php

namespace App\Services;

use App\Interfaces\WeatherProviderInterface;

class OpenMeteoWeatherProvider implements WeatherProviderInterface
{
    public function getData(float $long, float $lat, int $hour): array | false
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.open-meteo.com/v1/forecast?latitude='.$long.'&longitude='.$lat.'&hourly=temperature_2m&forecast_days=1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch))
        {
            Log::error("OpenMeteo - Curl Error: " . curl_error($ch));
            curl_close($ch);
            return false;
        }
        
        curl_close($ch);

        $result_json = json_decode($result, true);

        if (!is_array($result_json) || !isset($result_json["hourly"]["temperature_2m"][$hour]))
        {
            Log::error("OpenMeteo - error parsing json response");
            return false;
        }

        if (isset($result_json["error"]) && $result_json["error"] === true)
        {
            Log::error("OpenMeteo - Error: " . $result_json["reason"]);
            return false;
        }
        
        return array(
            "temp" => $result_json["hourly"]["temperature_2m"][$hour]
        );
    }
}