<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurements;
use App\Repository\MeasurementsRepository;
use App\Repository\LocationRepository;

class WeatherUtil
{
    public function __construct(
        private readonly MeasurementsRepository $measurementsRepository,
        private readonly LocationRepository $locationRepository)
    {}

    /**
     * @return Measurements[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        return $this->measurementsRepository->findByLocation($location);
    }

    /**
     * @return Measurements[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findOneByCityCountry($city, $countryCode);
        if (!$location) {
            return [];
        }
        return $this->getWeatherForLocation($location);
    }
}
