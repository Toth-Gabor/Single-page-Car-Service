<?php

use App\Service;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class ServicesTableSeeder extends DatabaseSeeder
{
    /**
     * Feltölti a 'services' táblát adatokkal.
     * @return void
     * @throws FileNotFoundException
     */
    public function run()
    {
        $data = $this->getJson('data/services.json');
        foreach ($data as $service) {
            Service::create(array(
                'client_id' => $service->client_id,
                'car_id' => $service->car_id,
                'lognumber' => $service->lognumber,
                'event' => $service->event,
                'eventtime' => $service->eventtime,
                'document_id' => $service->document_id
            ));
        }
    }
}
