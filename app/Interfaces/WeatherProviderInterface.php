<?php

namespace App\Interfaces;

interface WeatherProviderInterface
{
    public function getData(float $long, float $lat);
}