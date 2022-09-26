<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobsController extends Controller
{
    public function index(Request $request = null, $full = false)
    {
        $is_full = false;
        $carController = new CarsController();
        $api = new ApiController();
        list($cars, $car) = $carController->prepareUserCars(false, $request->input('selected') ? $request : null);
        $data = $api->prepareData();
        list($works, $products) = $this->prepareData($data, $car);
        $year_ago = Carbon::now()->addYear(-1);
        if ($works || $products) {
            $is_full = true;
        }
        if ($full) {
            $works = $works->groupBy('Name')->sortBy('Date');
            $products = $products->groupBy('Name')->sortBy('Date');
            $is_full = false;
        } else {
            $works = $works->where('Date', '>', $year_ago)->groupBy('Name')->sortBy('Date');
            $products = $products->where('Date', '>', $year_ago)->groupBy('Name')->sortBy('Date');
        }
        return view('custom.all_jobs', compact('works', 'products', 'cars', 'car', 'full', 'is_full'));
    }

    public function indexFull(Request $request)
    {
        return $this->index($request, true);
    }

    public function search(Request $request,  $full = true)
    {
        $is_full = true;
        $search = $request->input('search');
        if ($search == '') {
            return $this->index($request);
        }
        $carController = new CarsController();
        list($cars, $car) = $carController->prepareUserCars(false, $request);
        $api = new ApiController();
        $data = $api->prepareData();
        list($works, $products) = $this->prepareData($data, $car);

        $works = $works->filter(function ($item, $key) use ($search) {
            $str = mb_stripos($item->Name, $search, 0, 'UTF-8');
            if ($str !== false) {
                return $item;
            }
        });
        $products = $products->filter(function ($item, $key) use ($search) {
            $str = mb_stripos($item->Name, $search, 0, 'UTF-8');
            if ($str !== false) {
                return $item;
            }
        });
        $works = $works->groupBy('Name');
        $products = $products->groupBy('Name');
        return view('custom.all_jobs', compact('works', 'products', 'cars', 'car', 'search',  'full', 'is_full'));
    }

    /**
     * @param $data
     * @param $car
     * @return array
     */
    public function prepareData($data, $car)
    {
        $works = collect();
        $products = collect();
        foreach ($data as $datum) {
            foreach ($datum['woks'] as $wok) {
                $wok->CarName = $datum['CarName'];
                $wok->RegistrationNo = Str::words($wok->CarName, 1, '');
                $wok->CarOdometer = $datum['CarOdometer'];
                $wok->Date = Carbon::parse($wok->Date);
                if ($car->ID !== 0) {
                    if ($wok->RegistrationNo == $car->RegistrationNo) {
                        $works->push($wok);
                    }
                } else {
                    $works->push($wok);
                }
            }
            foreach ($datum['products'] as $product) {
                $product->CarName = $datum['CarName'];
                $product->RegistrationNo = Str::words($wok->CarName, 1, '');
                $product->CarOdometer = $datum['CarOdometer'];
                $product->Date = Carbon::parse($product->Date);
                if ($car->ID !== 0) {
                    if ($product->RegistrationNo == $car->RegistrationNo) {
                        $products->push($product);
                    }
                } else {
                    $products->push($product);
                }

            }
        }

        return array($works, $products);
    }
}
