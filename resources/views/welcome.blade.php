{{-- Extends layout --}}
@extends('layouts.default')

{{-- Content --}}
@section('content')
    <div class="wrapper">
        <section class="block-auth">
            <div class="auth-popup">
                {{--<div class="auth-popup-logo">--}}
                {{--<a href="{{route('main')}}"><img src="{{asset('img/logo.png')}}" alt="" width="72"></a>--}}
                {{--</div>--}}
                <div class="auth-popup-title">
                    {{session()->get('name', 'Клиент')}}
                </div>
                <button class="nav-btn" type="button" data-toggle="modal" data-target="#diagnosticModal">
                    <b>Записатись</b>
                </button>
                <button class="nav-btn " onclick="window.location.href = '{{route('all.jobs')}}'">
                    Вся історія
                </button>
                <button class="nav-btn" onclick="window.location.href = '{{route('recommendation')}}'">
                    Рекомендації
                </button>
                <button class="nav-btn " onclick="window.location.href = '{{route('index.acts')}}'">
                    Акти виконаних робіт
                </button>
                <button class="nav-btn " onclick="window.location.href = '{{route('talon')}}'">
                    Мої талони
                </button>
                <div class="phone-main">
                    <a href="tel:+380662050303">(066) 205 03 03 - Олександр</a>
                    <a href="tel:+380990206700 ">(099) 02 06 700 - Дмитро</a>
                </div>

            </div>
        </section>
        <div id="example">

        </div>
    </div>
@endsection
@section('scripts')
    <script async>
        $(".preloader").css('display', 'block');
        fetch('/prepare-all-data')
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                $(".preloader").css('display', 'none');
            }).then(() => {
            @if (\Session::get('password') == \Session::get('phone'))
            Swal.fire({
                icon: 'error',
                title: 'Попередження',
                text: 'Ваш пароль встановлено за замовчуванням будь ласка змініть його',
                showConfirmButton: false,
                footer: '<a href="{{route('index.changePassword')}}"><b>Натисніть щоб перейти до зміна пароля</b></a>'
            })
                {{--Swal.fire({--}}
                    {{--title: '<strong>HTML <u>example</u></strong>',--}}
                    {{--html:--}}
                    {{--'You can use <b>bold text</b>, ' +--}}
                    {{--'<a href="//sweetalert2.github.io">links</a> ' +--}}
                    {{--'and other HTML tags',--}}
                    {{--// title: 'Ваш пароль встановлено за замовчуванням будь ласка змініть його',--}}
                    {{--icon: 'warning',--}}
                    {{--html:'<a href="{{route('index.changePassword')}}">Зміна пароля</a>',--}}
                    {{--showConfirmButton: false,--}}
                {{--});--}}
            @endif
        });
    </script>
@endsection