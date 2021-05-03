<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\City;
use App\Entity\Flight;
use App\Entity\User;
use App\Services\FlightService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Validator\Constraints\Time;

class AppFixtures extends Fixture
{
    private $flightService;
    private $passwordEncoder;


    /**
     * On injecte un service dans le constructeur de Fixtures
     *
     * @param FlightService $fs
     */
    function __construct(FlightService $fs, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->flightService = $fs;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        /**
         * 
         * City
         */

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

        /**
         * 
         * Flight
         */

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


        /**
         * 
         * User
         */


        $admin = new User;
        $pwdcrypted = $this->passwordEncoder->encodePassword($admin, 'secret1');
        $admin
            ->setEmail('admin@capitalairways.com')
            ->setPseudo('Captain')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($pwdcrypted);
        $manager->persist($admin);


        $user = new User;
        $pwdcrypted = $this->passwordEncoder->encodePassword($user, 'secret2');
        $user
            ->setEmail('macfly@capitalairways.com')
            ->setPseudo('MacFly')
            ->setRoles(['ROLE_USER'])
            ->setPassword($pwdcrypted);
        $manager->persist($user);


        $manager->flush();
    }
}
