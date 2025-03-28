<?php

namespace App\Enum;

enum ShortWeatherDescription: string
{
    case SUNNY = 'SUNNY';
    case CLOUDY = 'CLOUDY';
    case RAINY = 'RAINY';
    case PARTLY_CLOUDY = 'PARTLY_CLOUDY';
    case SNOWY = 'SNOWY';
    case STORMY = 'STORMY';
}
