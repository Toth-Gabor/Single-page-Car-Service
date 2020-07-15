<?php

use App\Client;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class ClientsTableSeeder extends DatabaseSeeder
{
    /**
     * Feltölti a 'clients' táblát adatokkal.
     * @return void
     * @throws FileNotFoundException
     */
    public function run()
    {
        $data = $this->getJson('data/clients.json');
        foreach ($data as $client) {
            Client::create(array(
                'id' => $client->id,
                'name' => $client->name,
                'idcard' => $client->idcard,
            ));
        }
    }
}
