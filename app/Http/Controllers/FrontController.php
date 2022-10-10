<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Arturgrigio\GoogleCalendar\Event;
use Illuminate\Support\Facades\Session;


class FrontController extends Controller
{
    const SITE = 'http://95.217.38.198/csws/';

    public function loginForAdmin()
    {
        return view('auth.loginForAdmin');
    }

    public function loginClient(Request $request)
    {
        session()->put('phone', $request->phone);
        session()->put('password', $request->password);
        return redirect()->route('main');
    }

    public function aoth()
    {
        $client = new Google\Client();
        $client->useApplicationDefaultCredentials();

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        }

        dd($token);
    }

    public function logoutClient()
    {
        Session::flush();
        session()->regenerate();
        session()->forget('phone');
        session()->forget('password');
        session()->forget('name');
        session()->forget('prepareData');
        session()->forget('prepareDataForRecomendation');
        session()->forget('prepareAllData');
        return redirect()->route('login');
    }

    public function index()
    {
        $username = session()->get('phone');
        $password = session()->get('password');
        if (session()->has('phone')) {
            return redirect()->route('main');
        }
        return redirect()->route('login');
    }


    public function managerConnect(Request $request)
    {
        $url = 'https://api.telegram.org/bot979591455:AAFwrljsRJZbir-TbM1zuu7FdHEjQePxmi0/sendMessage';
        if ($request->phone) {
            $phone = $request->phone;
        } else {
            $phone = session()->get('phone');
        }
        if (session()->has('name')) {
            $name = session()->get('name');
        } else {
            $name = 'Клиент не авторизован';
        }

        $text = 'Телефон: ' . $phone . PHP_EOL . ' Имя: ' . $name . PHP_EOL . ' Вопрос: ' . $request->question;

        $params = [
            'chat_id' => -1001576485245,
            'text' => $text
        ];
        $this->send_request_get($url, $params);
        return redirect()->back()->with('success', 'Спасибо мы получили Ваше сообщение!');
    }

    public function excel()
    {
        $string = public_path('cliensts') . '/clients.xlsx';
        Excel::load($string, function ($reader) {

            foreach ($reader->all() as $items) {
                foreach ($items as $item) {
                    if (isset($item['telefon']) && !empty($item['telefon'])) {
                        DB::table('users')->insert([
                            'name' => $item['nazvanie'],
                            'phone' => $item['telefon'],
                            'password' => bcrypt($item['telefon']),
                        ]);
                    }
                }

            }

        });
        dd('done');
    }

    public function send_request_get($url, $params)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
    }

    public function managerConnect2($text)
    {
        $url = 'https://api.telegram.org/bot979591455:AAFwrljsRJZbir-TbM1zuu7FdHEjQePxmi0/sendMessage';

        $params = [
            'chat_id' => -1001576485245,
            'text' => $text
        ];
        $this->send_request_get($url, $params);
        return true;
    }

    public function addGoogleDiagnosticOrderNoAuthorization(Request $request)
    {
        $usePhone = $request->phone;
        $eventName = 'Онлайн) ' . $request->another_car . ' ' . $usePhone . ' ' . '  ' . $request->question;
        if ($request->day == 'tomorrow') {
            $str = Carbon::tomorrow()->format('d-m-Y') . 'T' . $request->time;
        } else {
            $str = Carbon::today()->format('d-m-Y') . 'T' . $request->time;
        }
        $time = Carbon::parse($str);

        $event = new Event;
        $event->name = $eventName;
        $event->startDateTime = $time;
        $event->endDateTime = $time->addMinute(30);
        $event->save();
        $text = "Новая запись на развал. " . $eventName . " Время: " . $time->addMinute(-30)->format('d-m G:i');
        $this->managerConnect2($text);
        return redirect()->back()->with('success', 'Спасибо Вы записаны на развал-схождение');
    }

    public function indexRegistration()
    {
        return view('auth.registration.index');
    }

    public function registration(Request $request)
    {
        $password = $request->input('password');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $name = $request->input('name');
        $link = 'cs/registration';
        $data = [
            'Email' => $email,
            'Password' => $password,
            'Name' => $name,
            'Phone1' => $phone,
        ];

        list($response, $status) = $this->send_request_post($link, $data);

        if ($status != 200) {
            return redirect()->back()->with('error', 'Щось пішло не так. Спробуйе ще раз');
        }
        return redirect()->back()->with('success', 'Дякуємо за реєстрацію');
    }

    public function send_request_post($url, $params)
    {

        $link = $this::SITE . $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array($response, $httpcode);
    }

}
