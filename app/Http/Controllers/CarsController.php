<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarsController extends Controller
{
    const SITE = 'http://95.217.38.198/csws/';
    /**
     * @param Request $request
     * @param $isAllCars boolean
     * @return array
     */
    public function prepareUserCars($isAllCars = true, Request $request = null)
    {
        $api = new ApiController();
        $url = $this::SITE . 'cs/usercars';
        $cars = $api->send_request_get($url);
        $cars = collect($cars);
        if ($isAllCars) {
            $car = json_decode('{"ID":0, "Brand":"Всі автомобілі", "Model":"", "RegistrationNo":""}');
            $cars->push($car);
        }
        if (isset($request) && $request->input('selected')) {
            $car = $cars->where('ID', $request->input('selected'))->first();
        } else {
            $car = $cars->last();
        }
        $key = $cars->search(function ($item) use ($car) {
            return $item->ID == $car->ID;
        });

        $cars->forget($key);
        return array($cars, $car);
    }
}
