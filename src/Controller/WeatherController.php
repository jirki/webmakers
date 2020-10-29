<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\WeatherRequestService;
use App\Service\WeatherService;
use App\Entity\Weather;
use Symfony\Component\HttpFoundation\Request;

class WeatherController extends AbstractController
{
    /**
     * @Route("/api/weather/lat/{lat}/long/{long}", name="weather")
     */
    public function getWeatherFromCords(float $lat, float $lng, WeatherRequestService $weatherRequestService, Request $request)
    {   
        $weatherRequestService->makeWeatherRequest($lat, $lng);

        if (200 !== $weatherRequestService->getRequestStatusCode())
        {
            return $this->json([
                'code'      => $weatherRequestService->getRequestStatusCode(),
                'message'   => $weatherRequestService->getRequestMessage()
            ]);
        }

        $weatherService = new WeatherService($weatherRequestService->getRequestContent(), $lat, $lng);

        if ($request->isMethod('post')) 
        {
            $weatherEntity = $weatherService->getEntity();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($weatherEntity);
            $entityManager->flush();
        }
        
        return $this->json($weatherService->getCollectedData());
    }

    /**
     * @Route("/api/weather/all", name="weather_all")
     */
    public function getAll()
    {
        $weatherRespository = $this->getDoctrine()->getRepository(Weather::class);
        
        $allWeathers    = $weatherRespository->findAll();
        $maxTemp        = $weatherRespository->getMaxTemp();
        $minTemp        = $weatherRespository->getMinTemp();
        $avgTemp        = $weatherRespository->getAvgTemp();
        $countResults   = $weatherRespository->getAllResults();
        $mostPopular    = $weatherRespository->getMostPopularCity();

        return $this->json([
            'all'       => $allWeathers,
            'max'       => $maxTemp,
            'min'       => $minTemp,
            'avg'       => (is_numeric($avgTemp['avg_temp'])) ? number_format($avgTemp['avg_temp'], 2) : 0,
            'count'     => $countResults,
            'popular'   => $mostPopular
        ]);
    }
}
