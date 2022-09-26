<?php

namespace App\Http\Controllers;

use Arturgrigio\GoogleCalendar\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PagesController extends Controller
{
    const SITE = 'http://95.217.38.198/csws/';
    private $access_token = '';
    private $authorization = [];

    public function getToken()
    {
        $username = session()->get('phone');
        $password = session()->get('password');
        if (Auth::user()) {
            if (Auth::user()->is_admin == 1) {
                session()->put('admin', 1);
            }
        }

        if (isset($username) && $username !== null && isset($password) && $password !== null) {
            $url = $this::SITE . 'authorize';
            $data = [
                'username' => $username,
                'password' => $password,
                'grant_type' => 'password'
            ];
            $pr = $this->send_request_post_auth($url, $data);
            if (isset(json_decode($pr)->access_token) && !empty(json_decode($pr)->access_token)) {
                $this->access_token = json_decode($pr)->access_token;

                $authorization = [
                    'Content-Type:application/json',
                    'Authorization: Bearer ' . $this->access_token
                ];
                return $authorization;
            } else {
                return redirect()->route('login');
            }
        }

    }

    public function userInfo()
    {
        $url = $this::SITE . 'cs/user';
        $user = $this->send_request_get($url);
        return $user;

    }

    public function userCars()
    {
        $url = $this::SITE . 'cs/usercars';
        $cars = $this->send_request_get($url);
        return $cars;
    }

    public function UpdateProfile()
    {
        $url = $this::SITE . 'cs/updateprofile';
        $data = [
            'Name' => '03516 G Слатвицкий Валерий Николаевич',
            'Phone2' => '+380955543854',
        ];
        $works = $this->send_request_post($url, $data);
        dd($works);

    }

    public function googleCheckAvailableTime(Request $request)
    {
        $dataList = [
            '9:00',
            '9:30',
            '10:00',
            '10:30',
            '11:00',
            '11:30',
            '12:00',
            '12:30',
            '13:00',
            '13:30',
            '14:00',
            '14:30',
            '15:00',
            '15:30',
            '16:00',
            '16:30',
            '17:00',
            '17:30',
        ];
        $today = Carbon::now();
        if ($request->day == 'tomorrow') {
            $sdate = $edate = Carbon::today()->addDay(1);
        } else {
            $sdate = $edate = Carbon::now();

        }

        $events = Event::get();
        $dataTimes = collect();
        if ($sdate->dayOfWeekIso == 7) {
            return [];
        }


        foreach ($events as $event) {
            if ($event->isAllDayEvent()) {
                return [];
            }
            $eday = Carbon::parse($event->endDateTime)->format('d-m-Y');
            if ($event->endDateTime >= $sdate && $eday == $sdate->format('d-m-Y')) {
                $endDateTime = Carbon::parse($event->endDateTime);
                $startDateTime = Carbon::parse($event->startDateTime);
                $dataTimes = $this->createEventTimeBy30Minutes($dataTimes, $endDateTime, $startDateTime);

            }

        }


        foreach ($dataTimes as $time) {
            unset($dataList[array_search($time, $dataList)]);
        }
        if ($sdate->day === $today->day) {
            foreach ($dataList as $item) {
                if ($today->hour >= $item) {
                    unset($dataList[array_search($item, $dataList)]);
                }
            }
        }
        return $dataList;
    }

    public function createEventTimeBy30Minutes(Collection $dataTimes, Carbon $endDateTime, Carbon $startDateTime)
    {
        if ($endDateTime > $startDateTime) {
            $dataTimes->push($startDateTime->format('G:i'));
            $startDateTime->addMinute(30);
            $this->createEventTimeBy30Minutes($dataTimes, $endDateTime, $startDateTime);
        }
        return $dataTimes;
    }

    public function checkAvailableTime(Request $request)
    {
        $today = Carbon::now();
        $dataList = [
            '10:00',
            '11:00',
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00'
        ];
        if ($request->day == 'tomorrow') {
            $sdate = $edate = Carbon::today()->addDay(1);
        } else {
            $sdate = $edate = Carbon::today();
        }
        if ($sdate->dayOfWeekIso == 7) {
            return [];
        }
        $url = $this::SITE . 'cs/stopost/99/' . $sdate->format('Y-m-d') . '/' . $edate->format('Y-m-d');

        $dataTimes = $this->send_request_get($url);

        foreach ($dataTimes as $time) {
            $t = Carbon::parse($time->StartTime)->format('G:i');
            if (in_array($t, $dataList))
                unset($dataList[array_search($t, $dataList)]);
        }
        if ($sdate->day === $today->day) {
            foreach ($dataList as $item) {
                if ($today->hour >= $item) {
                    unset($dataList[array_search($item, $dataList)]);
                }
            }
        }
        return $dataList;
    }

    public function addGoogleDiagnosticOrder(Request $request)
    {
        $user = $this->userInfo();
        $car = $request->car;
        $eventName = $request->another_car . ' ' . $user->Name . ' ' . $request->question . ' ' . $user->Phone;
        if ($request->day == 'tomorrow')
            $str = Carbon::tomorrow()->format('d-m-Y') . 'T' . $request->time;
        else
            $str = Carbon::today()->format('d-m-Y') . 'T' . $request->time;
        $time = Carbon::parse($str);
        if ($car != 'another')
            foreach (session()->get('cars') as $item)
                if ($request->car == $item->ID)
                    $eventName = $item->RegistrationNo . ' > ' . $item->Brand . ' ' . $item->Model . ' ' . $user->Name . '  ' . $request->questioncheckAvailableTime . ' ' . $user->Phone;


        $event = new Event;
        $event->name = $eventName;
        $event->startDateTime = $time;
        $event->endDateTime = $time->addMinute(30);
        $event->save();


        $text = "Новая запись на развал. " . $eventName . " Время: " . $time->addMinute(-30)->format('d-m G:i');
        $this->managerConnect($text);
        return redirect()->back()->with('success', 'Спасибо Вы записаны на развал-схождение');
    }

    public function managerConnect($text)
    {
        $url = 'https://api.telegram.org/bot979591455:AAFwrljsRJZbir-TbM1zuu7FdHEjQePxmi0/sendMessage';

        $params = [
            'chat_id' => -1001576485245,
            'text' => $text
        ];
        $this->send_request_get_tg($url, $params);
        return true;
    }

    public function send_request_get_tg($url, $params)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
    }

    public function addDiagnosticOrder(Request $request)
    {
        $user = $this->userInfo();
        $url = $this::SITE . 'cs/order';
        if ($request->day == 'today') {
            $startTime = Carbon::today()->format('Y-m-d') . 'T' . $request->time . ':00';
            $finishTime = Carbon::today()->format('Y-m-d') . 'T' . Carbon::parse($startTime)->addHours(1)->format('h:i') . ':00';
        } else {
            $startTime = Carbon::today()->addDay(1)->format('Y-m-d') . 'T' . $request->time . ':00';
            $finishTime = Carbon::today()->addDay(1)->format('Y-m-d') . 'T' . Carbon::parse($startTime)->addHours(1)->format('h:i') . ':00';
        }
        $data = [
            'Name' => $user->Name,
            'Phone' => $user->Phone,
            'Email' => '',
            'Address' => '',
            'Notes' => 'Онлайн запись',
            'Delivery' => '',
            'UserCarID' => $request->car,
//            'UserCarOdometr' => $car->Odometer,
            'StoPostID' => 99,
            'RemTypeID' => 6,
            'WorkReasonNotes' => $request->question,
            'WorkStartTime' => $startTime,
            'WorkFinishTime' => $finishTime,
            'Currency' => '',
            'DeliveryAmount' => 0.00,
            'ProductAmount' => 0.00,
            'WorksAmount' => 0.00,
            'Total' => 0.00,
            'StatusCode' => 'N',
            'DocCode' => 'U',
            'Works' => array([
                'ID' => 805,
                'Quantity' => 1.0,
                'Name' => '',
                'StdHour' => 0.00,
                'Price' => 0.00,
                'Total' => 0.00,
                'Currency' => '',
            ])
        ];
        $works = $this->send_request_post($url, $data);
        foreach (session()->get('cars') as $item)
            if ($request->car == $item->ID)
                $eventName = $item->RegistrationNo . ' > ' . $item->Brand . ' ' . $item->Model . ' ' . $user->Name . '  ' . $request->question . ' ' . $user->Phone;
        $time = Carbon::parse($startTime)->format('d-m G:i');
        $text = "Новая запись на диагностику. " . $eventName . " Время: " . $time;
        $this->managerConnect($text);
        return redirect()->back()->with('success', 'Спасибо Вы записаны на диагностику');
    }

    public function stopost()
    {
        $sdate = '2012-08-04';
        $edate = '2021-08-05';
        $url = $this::SITE . 'cs/stopost/99/' . $sdate . '/' . $edate;

        $time = $this->send_request_get($url);
        dd($time);
    }

    public function addOrder()
    {
        $user = $this->userInfo();
        $cars = $this->userCars();
        $car = $cars[0];
        //dd($cars);
        $url = $this::SITE . 'cs/order';
        $data = [
            'Name' => $user->Name,
            'Phone' => $user->Phone,
            'Email' => '',
            'Address' => '',
            'Notes' => 'Олайн запись',
            'Delivery' => '',
            'UserCarID' => $car->ID,
            'UserCarOdometr' => $car->Odometer,
            'StoPostID' => 99,
            'RemTypeID' => 6,
            'WorkReasonNotes' => 'TEST reason',
            'WorkStartTime' => "2021-07-20T13:00:00",
            'WorkFinishTime' => "2021-07-20T14:00:00",
            'Currency' => '',
            'DeliveryAmount' => 0.00,
            'ProductAmount' => 0.00,
            'WorksAmount' => 0.00,
            'Total' => 0.00,
            'StatusCode' => 'N',
            'DocCode' => 'U',
            // 'Products'=>array([
            //     // 'ID'=>null,
            //     'Quantity'=>1.00,
            //     'SupplierID'=>0,
            //     'CodeCat'=>1,
            //     'Producer'=>'',
            //     'Name'=>'test',
            //     'Price'=>0.00,
            //     'Total'=>0.00,
            //     'Currency'=>'',
            //     ]),
            'Works' => array([
                'ID' => 805,
                'Quantity' => 1.0,
                'Name' => '',
                'StdHour' => 0.00,
                'Price' => 0.00,
                'Total' => 0.00,
                'Currency' => '',
            ])
        ];

        $works = $this->send_request_post($url, $data);
        dd($works);

    }

    public function main()
    {
        if (!session()->has('users')) {
            $url = $this::SITE . 'cs/user';
            $user = $this->send_request_get($url);
            session()->put('name', $user->Name);
        }
        if (!session()->has('cars')) {
            $url = $this::SITE . 'cs/usercars';
            $cars = $this->send_request_get($url);
            session()->put('cars', $cars);
        }
        return view('welcome');
    }

    public function acts($id, $recType)
    {
        $url = $this::SITE . 'cs/user';
        $user = $this->send_request_get($url);
        $data = $this->prepareDataForOneAct($id, $recType);
        $array = $this->prepareAllData();

        $k = array_search($id, array_column($array, 'ID'));
        $next = null;
        if (count($array) > $k + 1) {
            $next = $array[$k + 1];
        }

        $recommendations = $this->prepareDataForOneActRec($id, $recType);
        return view('custom.acts', compact('data', 'next', 'recommendations', 'user'));
    }


    public function indexActs()
    {
        if (!session()->has('users')) {
            $url = $this::SITE . 'cs/user';
            $user = $this->send_request_get($url);
            session()->put('name', $user->Name);
        }
        $url = $this::SITE . 'cs/usercars';
        $cars = $this->send_request_get($url);
        $cars = collect($cars);
        $data = $this->prepareData();

        $group = collect();

        foreach ($data as $value) {
            $group->push($value);
        }
        $collection = collect($group)->map(function ($voucher) {
            return (object)$voucher;
        });
        $group = $collection->groupBy('year');
        $car = collect();
        $car->ID = 0;
        $car->Brand = 'Все автомобили';
        $car->Model = '';
        $car->RegistrationNo = '';
        return view('custom.index_acts', compact('group', 'cars', 'car'));
    }

    public function indexActsSelected(Request $request)
    {
        $url = $this::SITE . 'cs/usercars';
        $cars = $this->send_request_get($url);
        $cars = collect($cars);
        $car = $cars->where('ID', $request->selected)->first();

        $cars = $cars->filter(function ($item, $key) use ($car) {
            if ($item->RegistrationNo !== $car->RegistrationNo) {
                return $item;
            }
        });
        $data = $this->prepareData();
        $group = collect();
        foreach ($data as $value) {
            $group->push($value);
        }
        $collection = collect($group)->map(function ($voucher) {
            return (object)$voucher;
        });
        $RegistrationNo = $car->RegistrationNo;
        $group = $collection->filter(function ($item, $key) use ($RegistrationNo) {
            $str = stripos($item->CarName, $RegistrationNo);
            if ($str !== false) {
                return $item;
            }
        });
        $group = $group->groupBy('year');
        $handleCar = collect();
        $handleCar->ID = 0;
        $handleCar->Brand = 'Все автомобили';
        $handleCar->Model = '';
        $handleCar->RegistrationNo = '';
        $cars->push($handleCar);
        return view('custom.index_acts', compact('group', 'cars', 'car'));
    }

    public function indexActsSelectedRecomendation(Request $request)
    {
        $url = $this::SITE . 'cs/usercars';
        $cars = $this->send_request_get($url);
        $cars = collect($cars);
        if ($request->input('selected') == 0) {
            return $this->recommendation();
        }
        $allCar = json_decode('{"ID":0, "Brand":"Всі автомобілі", "Model":"", "RegistrationNo":""}');
        $cars->push($allCar);
        $car = $cars->where('ID', $request->input('selected'))->first();
        $cars = $cars->filter(function ($item, $key) use ($car) {
            if ($item->RegistrationNo !== $car->RegistrationNo) {
                return $item;
            }
        });
        $data = $this->prepareDataForRecomendation();
        $works = collect();
        foreach ($data as $datum) {
            foreach ($datum['woks'] as $wok) {
                $wok->CarName = $datum['CarName'];
                $wok->RegistrationNo = Str::words($wok->CarName, 1, '');
                $wok->CarOdometer = $datum['CarOdometer'];
                $wok->Date = Carbon::parse($wok->Date);
                if ($wok->RegistrationNo == $car->RegistrationNo) {
                    $works->push($wok);
                }
            }
        }
        $to = Carbon::now()->addMonth(-3);
        $works = $works->where('Date', '>', $to);
        return view('custom.recomendation', compact('works', 'cars', 'car'));
    }


    public function recommendation()
    {
        list($cars, $car) = $this->prepareUserCars();
        $data = $this->prepareDataForRecomendation();
        $works = collect();

        foreach ($data as $datum) {
            foreach ($datum['woks'] as $wok) {
                $wok->CarName = $datum['CarName'];
                $wok->RegistrationNo = Str::words($wok->CarName, 1, '');
                $wok->CarOdometer = $datum['CarOdometer'];
                $wok->Date = Carbon::parse($wok->Date);
                $works->push($wok);
            }
        }

        $to = Carbon::now()->addMonth(-3);
        $works = $works->where('Date', '>', $to);
        $works = $works->sortByDesc('Date');

        return view('custom.recomendation', compact('works', 'cars', 'car'));
    }

    public function recommendationAll(Request $request)
    {
        list($cars, $car) = $this->prepareUserCars(true, $request);
        $data = $this->prepareDataForRecomendation();
        $works = collect();
        foreach ($data as $datum) {
            foreach ($datum['woks'] as $wok) {
                $wok->CarName = $datum['CarName'];
                $wok->RegistrationNo = Str::words($wok->CarName, 1, '');
                $wok->CarOdometer = $datum['CarOdometer'];
                $wok->Date = Carbon::parse($wok->Date);
                if ($car->ID == 0) {
                    $works->push($wok);
                }
                if ($car->ID != 0 && $wok->RegistrationNo == $car->RegistrationNo) {
                    $works->push($wok);
                }
            }
        }
        $works = $works->sortByDesc('Date');
        return view('custom.recomendation', compact('works', 'cars', 'car'));
    }


    public function workgroup()
    {
        $url = $this::SITE . 'cs/workgroup/11';
        $workgroup = $this->send_request_get($url);
        print_r($workgroup);
        die();
    }

    public function searchRecomendation(Request $request)
    {
        $url = $this::SITE . 'cs/usercars';
        $cars = $this->send_request_get($url);
        $cars = collect($cars);
        $car = $cars->where('ID', $request->selected)->first();
        $cars = $cars->filter(function ($item, $key) use ($car) {
            if ($item->RegistrationNo !== $car->RegistrationNo) {
                return $item;
            }
        });
        $data = $this->prepareDataForRecomendation();
        $works = collect();
        foreach ($data as $datum) {
            foreach ($datum['woks'] as $wok) {
                $wok->CarName = $datum['CarName'];
                $wok->RegistrationNo = Str::words($wok->CarName, 1, '');
                $wok->CarOdometer = $datum['CarOdometer'];
                if ($wok->RegistrationNo == $car->RegistrationNo)
                    $works->push($wok);
            }
        }
        $search = $request->search;
        $works = $works->filter(function ($item, $key) use ($search) {
            $str = mb_stripos($item->Name, $search, 0, 'UTF-8');
            if ($str !== false) {
                return $item;
            }
        });
        $works = $works->groupBy('Name');
        return view('custom.recomendation', compact('works', 'cars', 'car', 'search'));
    }


    private function send_request_post_auth($link, $params)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($response, $header_size);
        curl_close($ch);
        return $body;
    }

    private function send_request_post($link, $params)
    {
        $this->authorization = $this->getToken();
        if (!$this->authorization || !is_array($this->authorization)) {
            abort(404);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization: Bearer ' . $this->access_token));
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($response, $header_size);
        curl_close($ch);
        return $body;
    }

    public function send_request_get($url)
    {
        $this->authorization = $this->getToken();
        if (!$this->authorization || !is_array($this->authorization)) {
            abort(404);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->authorization);
        $out = curl_exec($curl);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $body = substr($out, $header_size);
        curl_close($curl);
        return json_decode($body);
    }

    public function updateAllData()
    {
        session()->forget('prepareAllData');
        $data = $this->prepareAllData();
        return $data;
    }

    public function prepareAllData()
    {
        if (!session()->has('prepareAllData')) {
            $url = $this::SITE . 'cs/history';
            $orders = $this->send_request_get($url);
            $data = [];
            foreach ($orders as $order) {
                $no = preg_replace('/\d/', '', $order->No);
                if ($no == 'W') {
                    if ($order->DocCode == 'A' || $order->DocCode == 'F') {
                        $url = $this::SITE . 'cs/history/' . $order->ID . '/' . $order->RecType;
                        $work = $this->send_request_get($url);
                        $data[] = $work;
                    }
                }
            }
            session()->put('prepareAllData', $data);
        } else {
            $data = session()->get('prepareAllData');
        }
        return $data;
    }


    public function prepareData()
    {
        $data = [];
        $prWorks = $this->prepareAllData();
        foreach ($prWorks as $prWork) {
            $works = collect();
            foreach ($prWork->Works as $item) {
                if ($item->Group == 'Выполнено' && $item->WorkerName != '1Дефектовано!') {
                    $item->Date = Carbon::parse($prWork->Date)->format('d-m-Y');
                    $works->push($item);
                }
            }
            $products = collect();
            foreach ($prWork->Products as $item) {
                $item->Date = Carbon::parse($prWork->Date)->format('d-m-Y');
                $products->push($item);
            }
            $data[$prWork->ID]['woks'] = $works;
            $data[$prWork->ID]['products'] = $products;
            $data[$prWork->ID]['date'] = Carbon::parse($prWork->Date)->format('d-m-Y');
            $data[$prWork->ID]['year'] = Carbon::parse($prWork->Date)->format('Y');
            $data[$prWork->ID]['CarOdometer'] = $prWork->CarOdometer;
            $data[$prWork->ID]['CarName'] = $prWork->CarName;
            $data[$prWork->ID]['orderId'] = $prWork->ID;
            $data[$prWork->ID]['actId'] = $prWork->No;
            $data[$prWork->ID]['RecType'] = $prWork->RecType;
            $data[$prWork->ID]['status'] = $prWork->StatusCode == "A" ? 'Предварительно' : '';
        }

        return $data;

    }

    public function prepareAllDataForRecomendations()
    {
        if (!session()->has('prepareAllDataForRecomendations')) {
            $url = $this::SITE . 'cs/history';
            $orders = $this->send_request_get($url);
            $data = [];
            foreach ($orders as $order) {
                $no = preg_replace('/\d/', '', $order->No);
                if ($no == 'W') {
                    $url = $this::SITE . 'cs/history/' . $order->ID . '/' . $order->RecType;
                    $work = $this->send_request_get($url);
                    $data[] = $work;
                }
            }
            session()->put('prepareAllDataForRecomendations', $data);
        } else {
            $data = session()->get('prepareAllDataForRecomendations');
        }
        return $data;
    }

    public function prepareDataForRecomendation()
    {
        $data = [];
        $prWorks = $this->prepareAllDataForRecomendations();
        foreach ($prWorks as $prWork) {
            $works = collect();
            foreach ($prWork->Works as $item) {
                if ($item->WorkerName == '1Дефектовано!' || $item->Group == 'Комментарий') {
                    if ($item->Group != 'Наряд-заказ') {
                        $item->Date = Carbon::parse($prWork->Date)->format('d-m-Y');
                        $works->push($item);
                    }
                }
            }
            $products = collect();
            foreach ($prWork->Products as $item) {
                $item->Date = Carbon::parse($prWork->Date)->format('d-m-Y');
                $products->push($item);
            }
            $data[$prWork->ID]['woks'] = $works;
            $data[$prWork->ID]['products'] = $products;
            $data[$prWork->ID]['date'] = Carbon::parse($prWork->Date)->format('d-m-Y');
            $data[$prWork->ID]['year'] = Carbon::parse($prWork->Date)->format('Y');
            $data[$prWork->ID]['CarOdometer'] = $prWork->CarOdometer;
            $data[$prWork->ID]['CarName'] = $prWork->CarName;
            $data[$prWork->ID]['orderId'] = $prWork->ID;
            $data[$prWork->ID]['actId'] = $prWork->No;
            $data[$prWork->ID]['RecType'] = $prWork->RecType;
        }
        return $data;
    }

    protected function prepareDataForOneAct($orderId, $RecType)
    {
        $data = [];
        $url = $this::SITE . 'cs/history/' . $orderId . '/' . $RecType;
        $work = $this->send_request_get($url);
        $works = collect();
        foreach ($work->Works as $item) {
            if ($item->Group == 'Выполнено' && $item->WorkerName != '1Дефектовано!') {
                $item->Date = Carbon::parse($work->Date)->format('d-m-Y');
                $item->reason = 0;
                $works->push($item);
            } elseif ($item->ID == 1967) {

                $item->Date = Carbon::parse($work->Date)->format('d-m-Y');
                $item->reason = 1;
                $item->Notes .= '<br/>' . $work->WorkNotes->Reason;

                $works->push($item);
            }
        }
        $products = collect();
        foreach ($work->Products as $item) {
            $item->Date = Carbon::parse($work->Date)->format('d-m-Y');
            $products->push($item);
        }

        $data['woks'] = $works;
        $data['products'] = $products;
        $data = $this->preWorksForPage($work, $data, $orderId);
        $data['actId'] = $work->No;
        $data['RecType'] = $RecType;

        return $data;
    }

    protected function prepareDataForOneActRec($orderId, $RecType)
    {
        $data = [];
        $url = $this::SITE . 'cs/history/' . $orderId . '/' . $RecType;
        $work = $this->send_request_get($url);

        $works = collect();
        foreach ($work->Works as $item) {
            if ($item->WorkerName == '1Дефектовано!' || $item->Group == 'Комментарий') {
                if ($item->Group != 'Наряд-заказ') {
                    $item->Date = Carbon::parse($work->Date)->format('d-m-Y');
                    $works->push($item);
                }
            }
        }

        $data['woks'] = $works;
        $data = $this->preWorksForPage($work, $data, $orderId);
        $data['RecType'] = $RecType;
        $data['sumRec'] = $work->WorkAmount->Discount;
        return $data;
    }

    public function talon()
    {
        $array = $this->prepareAllData();
        $works = collect();
        foreach ($array as $item) {
            foreach ($item->Works as $work) {
                $work->Date = $item->Date;
                $work->Year = Carbon::parse($item->Date)->format('Y');
                $work->usage = false;
                $works->push($work);
            }

        }
        $tickets = $works->where('Group', 'Талони');
        foreach ($works as $work) {
            foreach ($tickets as $ticket) {
                $pos = str_contains($work->Description, $ticket->Code);
                if ($pos === true) {
                    $ticket->usage = true;
                }
            }
        }
        $usedTickets = $tickets->where('usage', true)->groupBy('Year');
        $unusedTickets = $tickets->where('usage', false)->groupBy('Year');
        $cars = [];
        $car = [];
        return view('custom.talon', compact('usedTickets', 'unusedTickets', 'cars', 'car'));
    }

    public function buyTalon()
    {
        $url = $this::SITE . 'cs/workgroup/63';
        $works = $this->send_request_get($url);
        dd($works);

    }

    /**
     * @param mixed $work
     * @param array $data
     * @param $orderId
     * @return array
     */
    protected function preWorksForPage($work, $data, $orderId)
    {
        $data['date'] = Carbon::parse($work->Date)->format('d-m-Y');
        $data['year'] = Carbon::parse($work->Date)->format('Y');
        $data['CarOdometer'] = $work->CarOdometer;
        $data['CarName'] = $work->CarName;
        $data['orderId'] = $orderId;
        return $data;
    }

    /**
     * @param Request $request
     * @param $isAllCars boolean
     * @return array
     */
    public function prepareUserCars($isAllCars = true, Request $request = null)
    {
        $url = $this::SITE . 'cs/usercars';
        $cars = $this->send_request_get($url);
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
