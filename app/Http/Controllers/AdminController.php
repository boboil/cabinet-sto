<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;


class AdminController extends Controller
{
    const SITE = 'http://95.217.38.198/csws/';
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function loadAdminModal(Request $request)
    {
        $pageController = new PagesController();
        $url = $this::SITE.'cs/usercars';
        $cars =  $pageController->send_request_get($url);
        $cars = collect($cars);
        $car = $cars->first();
        $cars = $cars->filter(function ($item, $key) use($car) {
            if ($item->RegistrationNo !== $car->RegistrationNo) {
                return $item;
            }
        });
        $pageController = new PagesController();
        $data = $pageController->prepareData();
        $works = collect();
        $products = collect();

        foreach ($data as $datum)
        {
            foreach ($datum['woks'] as $wok)
            {
                $wok->CarName = $datum['CarName'];
                $wok->RegistrationNo = Str::words($wok->CarName, 1, '');
                $wok->CarOdometer = $datum['CarOdometer'];
                if ($wok->RegistrationNo == $car->RegistrationNo)
                    $works->push($wok);
            }
            foreach ($datum['products'] as $product)
            {
                $product->CarName = $datum['CarName'];
                $product->RegistrationNo = Str::words($wok->CarName, 1, '');
                $product->CarOdometer = $datum['CarOdometer'];
                if ($product->RegistrationNo == $car->RegistrationNo)
                    $products->push($product);
            }
        }
        $item = '';
        if ($request->type == 'work')
        {
            $item = $works->where('ID', $request->id)->first();
        }
        if ($request->type == 'product')
        {
            $item = $products   ->where('ID', $request->id)->first();
        }
        return response()->json($item);
    }
    public function loadAdminModalRec(Request $request)
    {
        $pageController = new PagesController();
        $url = $this::SITE.'cs/usercars';
        $cars =  $pageController->send_request_get($url);
        $cars = collect($cars);
        $car = $cars->first();
        $cars = $cars->filter(function ($item, $key) use($car) {
            if ($item->RegistrationNo !== $car->RegistrationNo) {
                return $item;
            }
        });
        $data = $pageController->prepareDataForRecomendation();
        $works = collect();
        foreach ($data as $datum)
        {
            foreach ($datum['woks'] as $wok)
            {
                $wok->CarName = $datum['CarName'];
                $wok->RegistrationNo = Str::words($wok->CarName, 1, '');
                $wok->CarOdometer = $datum['CarOdometer'];
                $works->push($wok);

            }

        }
        $item = $works->where('ID', $request->id)->first();
        return response()->json($item);
    }
}
