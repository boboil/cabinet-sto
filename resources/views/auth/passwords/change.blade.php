@extends('layouts.default')
@section('content')
    <section class="block-auth">
        <div class="auth-popup">
            <div class="auth-popup-logo">
                <img src="{{asset('img/logo.png')}}" alt="" width="72">
            </div>
            <div class="auth-popup-title">
                Змінита пароля
            </div>
            <form class="auth-popup-form"  role="form" action="{{ route('changePassword') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="field">
                    <label class="input-label">
                        Старий пароль:
                    </label>
                    <input type="password" name="oldpassword" id="oldpassword" class="input" placeholder=""  required>
                </div>
                <div class="field">
                    <label class="input-label">
                        Новий пароль:
                    </label>
                    <input type="password" id="password1" name="password1" class="input" placeholder="" required>
                </div>
                <div class="field">
                    <label class="input-label">
                        Підтвердіть новий пароль:
                    </label>
                    <input type="password" id="password2" name="password2" class="input" placeholder="" required>
                </div>
                <button type="submit">
                    Змінити пароль
                </button>
            </form>
        </div>
    </section>
@endsection