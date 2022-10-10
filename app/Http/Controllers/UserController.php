<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    private $api;

    public function __construct()
    {
        $api = new ApiController();
        $this->api = $api;

    }

    public function index()
    {
        return view('auth.passwords.change');
    }

    public function changePassword(Request $request)
    {
        $oldpassword = $request->input('oldpassword');
        $password1 = $request->input('password1');
        $password2 = $request->input('password2');
        if (empty($oldpassword) || empty($password1) || empty($password2) || $password1 != $password2) {
            abort(404);
        }
        $link = 'cs/changepassword';
        $data = [
            'oldpassword' => $oldpassword,
            'password1' => $password1,
            'password2' => $password2
        ];
        list($response, $status) = $this->api->send_request_post($link, $data);

        if ($status != 200) {
            return redirect()->back()->with('error', 'Щось пішло не так спройте ще раз');
        }

        return redirect()->back()->with('success', 'Дякуємо Ваш пароль змінено!');
    }

}
