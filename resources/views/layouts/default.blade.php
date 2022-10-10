

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gold Auto</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{asset('css/styles.css?v=2.2')}}">
    <link rel="stylesheet" media="screen, print" href="{{asset('css/custom.css?v=2.3')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
    <link rel="shortcut icon" href="https://www.sto.sumy.ua/wp-content/uploads/fbrfg/favicon.ico">
    <link rel="apple-touch-icon" sizes="57x57" href="https://www.sto.sumy.ua/wp-content/uploads/fbrfg/apple-touch-icon-57x57.png">
    <link rel="icon" type="image/png" href="https://www.sto.sumy.ua/wp-content/uploads/fbrfg/favicon-32x32.png" sizes="32x32">
    <!-- <link rel="icon" type="image/png" href="favicon.png"> -->

</head>
<body>
<div class="wrapper">
    <div class="preloader">
        <div class="preloader__row">
            <div class="preloader__item"></div>
            <div class="preloader__item"></div>
        </div>
    </div>
    <header class="header">
        <div class="wrap">
            <div class="logo">
                <a href="{{route('main')}}"><img src="{{asset('img/logo.png')}}" alt="logo"></a>
            </div>
            <div class="manager-feedback">
                <ul class="social_chats">
                    <li>
                        <button type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModal">
                            Зв'язатися з нами!
                        </button>
                    </li>
                </ul>
            </div>
            <div class="header-phones">

            </div>
            <div class="buttons">
                <!-- <a href="/" class="return"></a> -->
                <button class="burger">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
            <nav class="menu">
                <b>
                    {{session()->get('name', 'Клиент')}}
                </b>
                <button type="button" data-toggle="modal" class="btn btn-warning" data-target="#diagnosticModal" style="margin: 1rem 0 1rem 0">
                    Записатись
                </button>
                <a href="{{route('all.jobs')}}">
                    <b>Вся історія</b>
                </a>
                <a href="{{route('recommendation')}}" style="color: red">
                   <b>Рекомендації</b>
                </a>
                <a href="{{route('index.acts')}}">
                    <b>Акти виконаних робіт</b>
                </a>
                <a href="{{route('talon')}}">
                    <b>Талони</b>
                </a>
                <a href="{{route('index.changePassword')}}">
                    <b>Зміна пароля</b>
                </a>
                <a href="{{ url('/logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                   <b>Вийти</b>
                </a>
                <form id="logout-form" action="{{ route('client.logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                <a href="tel:+380662050303">(066) 205 03 03 - Олександр</a>
                <a href="tel:+380990206700 ">(099) 02 06 700 - Дмитро</a>
                <button type="button" class="btn btn-info" onclick="updateData();">
                    Оновити дані
                </button>
            </nav>

        </div>
    </header>
        @yield('content')
    <footer>
        @include('includes._modal')
        @yield('modal')
    </footer>
</body>
<script type="text/javascript" src="{{asset('js/scripts.js?v=2')}}"></script>
<script type="text/javascript" src="{{asset('js/custom.js?v=3.2')}}"></script>
@yield('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
{{--<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>--}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

@if (\Session::has('success'))
    <script>
        Swal.fire({
            timer: 2500,
            title: '{!! \Session::get('success') !!}',
            icon: 'success',
            showConfirmButton: false,
        });
    </script>
@endif
@if (\Session::has('error'))
    <script>
        Swal.fire({
            timer: 2500,
            title: '{!! \Session::get('error') !!}',
            icon: 'error',
            showConfirmButton: false,
        });
    </script>
@endif
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-PB4W2G0B4B"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-PB4W2G0B4B');
</script>
</html>


