<?php

namespace App\Http\Middleware;

use ClientsTableSeeder;
use CarsTableSeeder;
use ServicesTableSeeder;
use Closure;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Feltölti az üres táblákat az app indulásakor az index oldal betöltése előtt.
class DbCheck
{
    /**
     * Ellenőrzi a táblákat
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws FileNotFoundException
     */
    public function handle($request, Closure $next)
    {
        if ($this->isTableEmpty('clients') == 0) {
            $clientsTableSeeder = new ClientsTableSeeder();
            $clientsTableSeeder->run();
        }

        if ($this->isTableEmpty('cars') == 0) {
            $carsTableSeeder = new CarsTableSeeder();
            $carsTableSeeder->run();
        }

        if ($this->isTableEmpty('services') == 0) {
            $servicesTableSeeder = new ServicesTableSeeder();
            $servicesTableSeeder->run();
        }
        return $next($request);
    }

    /**
     * Ellenőrzi hogy a tábla üres
     * @param string $tableName
     * @return int
     */
    private function isTableEmpty(string $tableName)
    {
        return DB::table($tableName)->count('*');
    }
}
