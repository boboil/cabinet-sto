<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
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

    public static function send_request_post_auth($link, $params)
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

    public function send_request_post($link, $params)
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
}
