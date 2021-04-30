<?php

namespace App\Services;

use PhpParser\Node\Stmt\Return_;

class FlightService
{
    public function getFlightNumber() : string {
        
        // Tableau de lettre de a à z 
        $lettres = range('A', 'Z');
        // mélange des lettres 
        shuffle($lettres);
        // Index de deux lettres dans un variable 
        $lettre1 = $lettres[2];
        $lettre2 = $lettres[5];
        // concatenation dans un resultat
        $resLettre = $lettre1.$lettre2;

        $number = mt_rand(1000,9999);

        return $resLettre.$number;
    }
}
