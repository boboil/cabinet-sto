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
                    Записатись
                </button>
                <button class="nav-btn" onclick="window.location.href = '{{route('recommendation')}}'">
                    Рекомендації/Повідомлення
                </button>
                <button class="nav-btn " onclick="window.location.href = '{{route('index.acts')}}'">
                    Акти виконаних робіт
                </button>
                <button class="nav-btn " onclick="window.location.href = '{{route('all.jobs')}}'">
                    Історія робіт та запчастин
                </button>
                <button class="nav-btn " onclick="window.location.href = '{{route('talon')}}'">
                    Талони
                </button>
                <div class="phone-main">
                    <a href="tel:+380662050303">(066) 205 03 03</a>
                    <a href="tel:+380990206700 ">(099) 02 06 700</a>
                </div>

            </div>
        </section>
        <div id="example">
            <example inline-template/>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $.ajax({
            method: "GET",
            url: "/prepare-all-data",
            beforeSend: function( xhr ) {
                $(".preloader").css('display', 'block');
            }
        })
            .done(function(  ) {
                $(".preloader").css('display', 'none');
            });
    </script>
@endsection