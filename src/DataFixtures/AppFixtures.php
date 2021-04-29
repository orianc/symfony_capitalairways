<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\City;
use App\Entity\Flight;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Validator\Constraints\Time;

class AppFixtures extends Fixture
{
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

        $flight1 = new Flight;
        $flight1
            ->setNumero(2878)
            ->setSchedule(\DateTime::createFromFormat('h:i','00:00'))
            ->setPrice(490)
            ->setReduction(false)
            ->setDeparture($tabCityObj[1])
            ->setArrival($tabCityObj[3])
            ;
            $manager->persist($flight1);

        

        $manager->flush();
    }
}
