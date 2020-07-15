<?php

use App\Car;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class CarsTableSeeder extends DatabaseSeeder
{
    /**
     * Feltölti a 'cars' táblát adatokkal.
     * @return void
     * @throws FileNotFoundException
     */
    public function run()
    {
        $data = $this->getJson('data/cars.json');
        foreach ($data as $car) {
            Car::create(array(
                'client_id' => $car->client_id,
                'car_id' => $car->car_id,
                'type' => $car->type,
                'registered' => $car->registered,
                'ownbrand' => $car->ownbrand,
                'accident' => $car->accident,
            ));
        }
    }
}
