<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\MeasurementsRepository;
use App\Service\WeatherUtil;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{city}', name: 'app_weather', requirements: ['country' => '[A-Z]{2}'])]
    public function city(
        #[MapEntity(mapping: ['country' => 'country', 'city' => 'city'])]
        string $country,
        string $city,
        LocationRepository $locationRepository,
        WeatherUtil $util
    ): Response {
        $location = $locationRepository->findOneByCityCountry($city, $country);
        if (!$location) {
            throw $this->createNotFoundException('Location not found');
        }

        $measurements = $util->getWeatherForLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
