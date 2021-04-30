# 1. Capital Airways
- [1. Capital Airways](#1-capital-airways)
- [2. Analyse du projet](#2-analyse-du-projet)
  - [2.1. Analyse du projet](#21-analyse-du-projet)
  - [2.2. Analyse fonctionnelle](#22-analyse-fonctionnelle)
  - [2.3. Couche métier](#23-couche-métier)
    - [2.3.1. Dégager les types de données](#231-dégager-les-types-de-données)
  - [2.4. Modélisation de la DB](#24-modélisation-de-la-db)
- [3. Configuration de l'API](#3-configuration-de-lapi)
  - [3.1. Database](#31-database)
  - [3.2. Entity Flight / City & Relation](#32-entity-flight--city--relation)
  - [3.3. Fixtures](#33-fixtures)
  - [3.4. Modification](#34-modification)
    - [3.4.1. Entité Flight](#341-entité-flight)
  - [3.5. Création et gestion du CRUD de `Flight`](#35-création-et-gestion-du-crud-de-flight)




# 2. Analyse du projet

## 2.1. Analyse du projet
Une cie de vols privés propose des trajets VIP vers des capitales européennes. 
Une API avec :
- Un système de login avec 2 types de users. `user` & `admin`.
- Un espace privé `admin` qui affiche des vols et propose des actions de type CRUD pour gérer les vols.

L'API à ce stade permet de gérer les vols de la journée en cours.

## 2.2. Analyse fonctionnelle
- A faire de même
- Compréhensible pour / dicté par le client.
- Peut donner lui à un **UseCase** UML

## 2.3. Couche métier

### 2.3.1. Dégager les types de données
  1. Vol || Trajet
  2. Capitale
  3. User

## 2.4. Modélisation de la DB
Avec MySQL workbench, on modélise un diagramme de class UML qui est basée sur l'analyse fonctionnelle.

![diagram_db](diagram_db.jpg)



# 3. Configuration de l'API
## 3.1. Database

Mettre en place dans le fichier .env le chemin vers la future db puis executer :

```bash
symfony console doctrine:database:create

```
## 3.2. Entity Flight / City & Relation

Mettre en place dans les Entity et faire les migrations :

```bash
symfony console make:entity
symfony console make:migration
symfony console doctrine:migration:migrate
```


## 3.3. Fixtures

J'importe le bundle nécessaire aux Fixtures :
composer require --dev doctrine/doctrine-fixtures-bundle

Puis je travail mes fixtures dans le dossier DataFixtures, AppFixtures, function `load()`. 

1.  Pour les villes, je décide de créer une liste des villes servies par la compagnie et de boucler dessus pour générer un Objet par ville et ses attributs. J'en profite pour stocker cet objet dans un Tableau d'objet des villes `$tabCityObj[] = $city;` :
 
 
 ```php
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
 ```

2. Pour les vols, il y aura un n° de vol statique. J'instancie un vol `$flight1 = new Flight;` et je remplis ses attributs. 

```php
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
```

3. **NOTION IMPORTANT** Les champs `departure` et `arrival` doivent être renseigné avec un objet pour faire le lien entre les deux tables. Elles font référence à la function suivante qui attend un objet `?City `:

```php
    public function setDeparture(?City $departure): self

```

Je le remplis avec `->setDeparture($tabCityObj[1])` qui fait référence à un des objets de mon tableau d'objet city créé precedemment.
 
## 3.4. Modification
### 3.4.1. Entité Flight
- On va ajouter un attribut `seat` et faire une nouvelle migration pour maj l'entité et ses attributs dans la DB. Si besoin on supprimera l'ancienne version de migrations.

```bash
symfony console make:entity <Flight> <seat> <int> <nullable:true>
symfony console make:migration
symfony console doctrine:migrations:migrate
```

- Modification des fixtures :
  - On alimente `->setSeat(200)`.
  - **Travail de boucle** : Ajout d'autre vols. Pour le moment le n° de vol et le schedule reste les mêmes.

```php

  for ($i = 0; $i < 5; $i++) {
      $flight = new Flight();
      $flight
          ->setNumero('AV12' . $i)
          ->setSchedule(\DateTime::createFromFormat('h:i', '00:00'))
          ->setPrice(mt_rand(100, 300))
          ->setReduction(false)
          ->setDeparture($tabCityObj[$i])
          ->setArrival($tabCityObj[$i + 1])
          ->setSeat(mt_rand(100, 200));

      $manager->persist($flight);
  }
```

## 3.5. Création et gestion du CRUD de `Flight`

1. On tape la commande `symfony console make:crud` qui facilite bien la vie...

```bash
 created: src/Controller/FlightController.php
 created: src/Form/FlightType.php
 created: templates/flight/_delete_form.html.twig
 created: templates/flight/_form.html.twig
 created: templates/flight/edit.html.twig
 created: templates/flight/index.html.twig
 created: templates/flight/new.html.twig
 created: templates/flight/show.html.twig
```

2. Gérer la class `Form/FlightType.php`

   - Tous les champs ne sont pas requis.
   - Gérer la relation avec `City` dans le FormType.
   - Ajouter les contraintes

3. Personnaliser le résultat

Faire appel à Bootstrap
Pour personnaliser les champs, Form Types Reference :
https://symfony.com/doc/current/reference/forms/types.html

4. On créé une classe `App\Services\FlightService`

Le service attribura un numero de vol lors de la création d'un vol.
  
  1. Utilisation pour les Fixtures
- On ne peux pas injecter directement dans la méthode `load()`
- Passer par un `__consctuct`
- Dans la méthode `load()` on injecte le service 

```php
    function __construct(FlightService $fs)
    {
        $this->flightService = $fs;    
    }

```
