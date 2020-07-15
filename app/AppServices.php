<?php


namespace App;


use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

// Az adatbázis műveleteket végző osztály
class AppServices
{
    /**
     * Az ügyfelek kilistázása
     *  - ügyfél azonosító
     *  - név
     *  - okmányazonosító
     * @param bool $isAjaxRequest
     * @return Factory|View
     */
    public static function getClients(bool $isAjaxRequest)
    {
        $clients = DB::table('clients')->paginate(6);
        if ($isAjaxRequest) {
            return view('clients', compact('clients'));
        }
        return view('layouts.app', compact('clients'));
    }

    /**
     * Ügyfél keresése név vagy idcard alapján
     *    - ügyfél azonosítója
     *  - ügyfél neve
     *    - ügyfél okmányazonosítója
     *    - autóinak darabszáma
     *    - összes szerviznapló bejegyzések száma ami az ügyfélhez tartozik (összes autójához tartozó)
     * @param string $name
     * @param int $idcard
     * @return JsonResponse
     */
    public static function getClientData(string $name = null, int $idcard = null)
    {
        $client = null;
        if ($name !== null) {
            $client = DB::select(
                'SELECT id, name, idcard, count(1) client_cars,
                 (SELECT count(1) FROM services WHERE services.client_id = c2.client_id GROUP BY c2.client_id) AS client_services
                  FROM clients c
                  LEFT JOIN cars c2 ON c.id = c2.client_id
                  WHERE c.name LIKE :name
                  GROUP BY 1, 2, 3, 5', ['name' => '%' . $name . '%']);
        }

        if ($idcard !== null) {
            $client = DB::select(
                'SELECT id, name, idcard, count(1) client_cars,
                 (SELECT count(1) FROM services WHERE services.client_id = c2.client_id GROUP BY c2.client_id) AS client_services
                  FROM clients c
                  LEFT JOIN cars c2 ON c.id = c2.client_id
                  WHERE c.idcard = :idcard
                  GROUP BY 1, 2, 3, 5', ['idcard' => $idcard]);
        }

        if (empty($client)) {
            return response()->json(['status' => 'Ügyfél nem található!']);
        } elseif (count($client) == 1) {
            return response()->json(['status' => 'ok', 'client' => json_encode($client[0])]);
        } elseif (count($client) > 1) {
            return response()->json(['status' => 'Több mint egy ügyfél!']);
        }
        return response()->json(['status' => 'Ismeretlen hiba!']);
    }

    /**
     * Az ügyfél autójához tartozó adatok összeállítása
     *  - autó sorszáma (car_id)
     *  - autó típusa
     *  - regisztrálás időpontja
     *  - saját márkás-e
     *  - balesetek száma
     *  - az autóhoz tartozó legutolsó (legnagyobb lognumber-rel rendelkező) szerviznapló bejegyzés eseményének neve
     *  - az autóhoz tartozó legutolsó (legnagyobb lognumber-rel rendelkező) szerviznapló bejegyzés eseményének időpontja
     * @param int $client_id
     * @return JsonResponse
     */
    public static function getCarsData(int $client_id)
    {
        if (AppServices::hasAnyCar($client_id)){
            if (AppServices::hasAnyService($client_id)){
                $cars = DB::select(
                    'SELECT c.client_id, c.car_id, c.type, c.registered, c.ownbrand, c.accident,
                          (
                              SELECT event
                              FROM services
                              WHERE c.car_id = car_id
                                AND c.client_id = client_id
                              ORDER BY lognumber DESC LIMIT 1
                          ) AS event,
                          (
                              SELECT eventtime
                              FROM services
                              WHERE c.car_id = car_id
                                AND c.client_id = client_id
                              ORDER BY lognumber
                              DESC LIMIT 1
                          ) AS eventtime
                           FROM cars c
                           WHERE client_id = :client_id',
                    [
                        'client_id' => $client_id
                    ]
                );
                return response()->json(['status' => 'ok', 'cars' => json_encode($cars)]);
            } else {
                return response()->json(['status' => 'Az ügyfélnek van autója de még nem volt szervizben!']);
            }
        }
        return response()->json(['status' => 'Az ügyfélnek nincs autója!']);
    }

    /**
     * Az ügyfél autójához tartozó szerviz adatok összeállítása
     *    - alkalom sorszáma (lognumber)
     *  - esemény neve
     *  - esemény időpontja
     *  - munkalap azonosító
     * @param int $client_id
     * @param int $car_id
     * @return JsonResponse
     */
    public static function getServicesData(int $client_id, int $car_id)
    {
        $services = DB::select(
            'SELECT car_id,
                          lognumber,
                          event,
                          document_id,
                          ifnull(
                              eventtime,
                              (
                                 SELECT registered
                                 FROM cars c
                                 WHERE c.client_id = s.client_id
                                 AND c.car_id = s.car_id
                              )
                          ) AS eventtime
                  FROM services s
                  WHERE client_id = :client_id
                  AND car_id = :car_id',
            [
                'client_id' => $client_id,
                'car_id' => $car_id
            ]
        );
        return response()->json(['status' => 'ok', 'services' => json_encode($services)]);
    }

    /**
     * Leellenőrzi hogy az ügyfélnek van autója
     * @param int $clientId
     * @return bool
     */
    private static function hasAnyCar(int $clientId)
    {
        return DB::table('cars')->where('client_id', '=', $clientId)->get()->isNotEmpty();
    }

    /**
     * Leellenőrzi hogy az ügyfélnek van szervizbejegyzése
     * @param int $clientId
     * @return bool
     */
    private static function hasAnyService(int $clientId)
    {
        return DB::table('services')->where('client_id', '=', $clientId)->get()->isNotEmpty();
    }



}
