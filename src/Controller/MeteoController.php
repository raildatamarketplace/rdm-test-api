<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api/meteo',  name: 'meteo')]
class MeteoController extends AbstractController
{
    private $client;
    private $meteoApiHost = "https://api.open-meteo.com/v1/forecast";

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/query', name: 'queryWeatherData')]
    public function getQueryWeatherData(Request $request): Response
    {
        $latitude = $request->query->get('latitude');
        $longitude = $request->query->get('longitude');
        $current_weather = true;
        $hourly = "temperature_2m";

        if (!$latitude || !$longitude) {
            return $this->json([
                'error' => [
                    'code' => 400,
                    'message' => 'The server cannot process the request due to a client error e.g. missing query parameters'
                ]
            ]);
        }

        // send GET request to Open-Meteo API
        $response = $this->client->request(
            "GET",
            $this->meteoApiHost . "?latitude={$latitude}&longitude={$longitude}&current_weather={$current_weather}&hourly={$hourly}"
        );

        $content = $response->toArray();

        return $this->json($content);             
    }
}