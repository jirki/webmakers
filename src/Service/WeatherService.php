<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Weather;

/**
 * Weather service class
 */
class WeatherService 
{   
    /**
     * weather object
     *
     * @var object
     */
    private $weather;

    /**
     * Latitude
     *
     * @var float
     */
    private $lat;

    /**
     * Longituted
     *
     * @var float
     */
    private $lng;
    
    /**
     * Constructor method
     *
     * @param object $weather
     * @param float $lat
     * @param float $lng
     */
    public function __construct(object $weather, float $lat, float $lng)
    {
        $this->weather  = $weather;
        $this->lat      = $lat;
        $this->lng      = $lng;
    }

    /**
     * Get cords method
     *
     * @return object
     */
    public function getCords(): object
    {
        return $this->weather
                    ->coord;
    }
    
    /**
     * Get weather method
     *
     * @return object
     */
    public function getWeather(): object
    {
        return $this->weather
                    ->weather[0];
    }
    
    /**
     * Get weather main data
     *
     * @return object
     */
    public function getMain(): object
    {
        return $this->weather
                    ->main;
    }
    
    /**
     * Get wind data
     *
     * @return object
     */
    public function getWind(): object
    {
        return $this->weather
                    ->wind;
    }

    /**
     * Get Clouds data
     *
     * @return object
     */
    public function getClouds(): object
    {
        return $this->weather
                    ->clouds;
    }

    /**
     * Get location name
     *
     * @return string
     */
    public function getLocationName(): string 
    {
        return $this->weather
                    ->name;
    }

    /**
     * Get collected data for popup
     *
     * @return array
     */
    public function getCollectedData(): array 
    {
        $weatherValues = [];

        $weatherValues['name']          = $this->getLocationName();
        $weatherValues['description']   = ($this->getWeather()) ? $this->getWeather()->description : '';
        $weatherValues['temp']          = ($this->getMain()) ? ceil($this->getMain()->temp - 273) : 0;
        $weatherValues['clouds']        = ($this->getClouds()) ? $this->getClouds()->all : 0;
        $weatherValues['wind']          = ($this->getWind()) ? $this->getWind()->speed : 0;

        return $weatherValues;
    }
    
    /**
     * Create entity 
     *
     * @return Weather
     */
    public function getEntity(): Weather 
    {   
        $weatherEntity = new Weather;
        $weatherEntity->setName($this->getLocationName());
        $weatherEntity->setDescription($this->getWeather()->description);
        $weatherEntity->setTemp(ceil($this->getMain()->temp - 273));
        $weatherEntity->setClouds($this->getClouds()->all);
        $weatherEntity->setWind($this->getWind()->speed);
        $weatherEntity->setLat($this->lat);
        $weatherEntity->setLng($this->lng);

        return $weatherEntity;
    }
}