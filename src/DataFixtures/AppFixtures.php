<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\City;
use App\Entity\Flight;
use App\Services\FlightService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Validator\Constraints\Time;

class AppFixtures extends Fixture
{
    private $flightService;


    /**
     * On injecte un service dans le constructeur de Fixtures
     *
     * @param FlightService $fs
     */
    function __construct(FlightService $fs)
    {
        $this->flightService = $fs;    
    }

    public function load(ObjectManager $manager)
    {
        $city = [
            'Paris',
            'Londres',
            'Madrid',
            'Amsterdam',
            'Rome',
            'Sofia'
        ];
        foreach ($city as $c) {
            $city = new City;
            $city->setName($c);
            $tabCityObj[] = $city;
            $manager->persist($city);
        }

        for ($i = 0; $i < 5; $i++) {
            $flight = new Flight();
            $flight
                ->setNumero($this->flightService->getFlightNumber())
                ->setSchedule(\DateTime::createFromFormat('h:i', '00:00'))
                ->setPrice(mt_rand(100, 300))
                ->setReduction(false)
                ->setDeparture($tabCityObj[$i])
                ->setArrival($tabCityObj[$i + 1])
                ->setSeat(mt_rand(100, 200));

            $manager->persist($flight);
        }

        $manager->flush();
    }
}
