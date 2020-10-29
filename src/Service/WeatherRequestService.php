<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherRequestService
{   
    /**
     * Http client 
     *
     * @var HttpClientInterface
     */
    private $client;

    /**
     * Variable to holding http request response
     *
     * @var Symfony\Component\HttpClient\Response\CurlResponse
     */
    private $weatherRequest;

    /**
     * Constant api weather key 
     */
    private const API_KEY = '2782634eed1c90284914691197d5acc4';

    /**
     * Contant api weather url
     */
    private const API_URL = 'http://api.openweathermap.org/data/2.5/weather';

    /**
     * Constructor method
     *
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client) 
    {
        $this->client = $client;
    }

    /**
     * Make weather request method
     *
     * @param float $lat
     * @param float $lng
     * @return void
     */
    public function makeWeatherRequest(float $lat, float $lng): void
    {   
        $query = $this->makeQuery($lat, $lng);

        $response = $this->client->request(
            'GET',
            $query
        );

        $this->weatherRequest = $response;
    }
    
    /**
     * Get request response status code method
     *
     * @return integer
     */
    public function getRequestStatusCode(): int
    {
        return $this->weatherRequest->getStatusCode();
    }
    
    /**
     * Get request message error method
     *
     * @return json|null
     */
    public function getRequestMessage(): ?json
    {
        if (200 !== $this->getRequestStatusCode()) {
            $content = $this->weatherRequest->getContent(false);

            return json_decode($content)->message;
        }
        
        return NULL;
    }

    /**
     * Get request content method
     *
     * @return object
     */
    public function getRequestContent(): object
    {
        return json_decode($this->weatherRequest->getContent());
    }

    /**
     * Prepare query request url
     *
     * @param float $lat
     * @param float $lng
     * @return string
     */
    private function makeQuery(float $lat, float $lng): string
    {
        $query = self::API_URL . '?appid=' . self::API_KEY;
        $query .= '&lat=' . $lat;
        $query .= '&lon=' . $lng;

        return $query;
    }
}