<?php

namespace App\Services;

use App\Interfaces\WeatherProviderInterface;
use Illuminate\Support\Facades\Log;

class WeatherApiWeatherProvider implements WeatherProviderInterface
{
    public function getData(float $long, float $lat, int $hour): array | false
    {
        $apiKey = env('WEATHERAPI_KEY');

        if (empty($apiKey))
        {
            Log::error("WeatherApi - API key not provided");
            return false;
        }

        $ch = curl_init();

        $url = "http://api.weatherapi.com/v1/current.json?q=$long,$lat&hour=$hour&key=$apiKey";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch))
        {
            Log::error("WeatherApi - Curl Error: " . curl_error($ch));
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        $result_json = json_decode($result, true);

        if (!is_array($result_json) || !isset($result_json["current"]["temp_c"]))
        {
            Log::error("WeatherApi - error parsing json response");
            return false;
        }

        if (isset($result_json["error"]))
        {
            Log::error("WeatherApi - ".$result_json["code"].": ". $result_json["message"]);
            return false;
        }

        return array(
            "temp" => $result_json["current"]["temp_c"]
        );        
    }
}