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

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        // send GET request to Open-Meteo API
        $response = $this->client->request(
            "GET",
            $this->meteoApiHost . "?latitude=52.52&longitude=13.41&current_weather=true&hourly=temperature_2m"
        );

        $content = $response->toArray();

        return $this->json($content);
    }

    #[Route('/generate', name: 'generateQuery')]
    public function getGenerateQueryParameters(Request $request): Response
    {
        $latitude = $request->query->get('latitude');
        $longitude = $request->query->get('longitude');
        $current_weather = $request->query->get('current_weather');
        $hourly = $request->query->get('hourly');

        if (!$hourly) {
            $hourly = "temperature_2m";
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