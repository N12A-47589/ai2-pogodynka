<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{country?}', name: 'app_weather')]
    public function city(
        string                $city,
        ?string               $country,
        WeatherUtil           $util,
        LocationRepository    $locationRepository,
    ): Response
    {
        $location = $locationRepository->findOneByCity($city, $country);
        if (!$location) {
            throw $this->createNotFoundException("Location not found");
        }
        $measurements = $util->getWeatherForLocation($location);
        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
