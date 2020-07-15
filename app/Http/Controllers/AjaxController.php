<?php

namespace App\Http\Controllers;

use App\AppServices;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AjaxController extends Controller
{
    /**
     * Az ügyfelek kilistázása
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $isAjaxRequest = $request->ajax();
        return AppServices::getClients($isAjaxRequest);
    }

    /**
     * Ügyfél keresése név vagy idcard alapján
     * @param Request $request
     * @return JsonResponse
     */
    public function searchClient(Request $request)
    {
        $request->validate([
            'client_id' => 'nullable|integer',
            'name' => 'nullable|string|max:255'
        ]);
        $name = trim($request->name);
        $idcard = $request->idcard;

        return AppServices::getClientData($name, $idcard);
    }

    /**
     * Az ügyfél autójához tartozó adatok összeállítása
     * @param Request $request
     * @return JsonResponse
     */
    public function getCars(Request $request)
    {
        $request->validate([
            'client_id' => 'required|integer'
        ]);

        $client_id = $request->client_id;

        return AppServices::getCarsData($client_id);
    }

    /**
     * Az ügyfél autójához tartozó szerviz adatok összeállítása
     * @param Request $request
     * @return JsonResponse
     */
    public function getServices(Request $request)
    {
        $request->validate([
            'car_id' => 'required|integer',
            'client_id' => 'required|integer'
        ]);

        $car_id = $request->car_id;
        $client_id = $request->client_id;

        return AppServices::getServicesData($client_id, $car_id);
    }
}
