<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gold Auto</title>
    <link rel="stylesheet" type="text/css" href="{{asset('css/styles.css?v=1')}}">
    <link rel="stylesheet" media="screen, print" href="{{asset('css/custom.css?v=2')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- <link rel="icon" type="image/png" href="favicon.png"> -->
    <link rel="shortcut icon" href="https://www.sto.sumy.ua/wp-content/uploads/fbrfg/favicon.ico">
    <link rel="apple-touch-icon" sizes="57x57" href="https://www.sto.sumy.ua/wp-content/uploads/fbrfg/apple-touch-icon-57x57.png">
    <link rel="shortcut icon" href="https://www.sto.sumy.ua/wp-content/uploads/fbrfg/favicon.ico">
    <link rel="apple-touch-icon" sizes="57x57" href="https://www.sto.sumy.ua/wp-content/uploads/fbrfg/apple-touch-icon-57x57.png">
    <link rel="icon" type="image/png" href="https://www.sto.sumy.ua/wp-content/uploads/fbrfg/favicon-32x32.png" sizes="32x32">

</head>

<body>
<div class="wrapper">
    <section class="block-auth">
        <div class="auth-popup">
            <div class="auth-popup-logo">
                <img src="{{asset('img/logo.png')}}" alt="" width="72">
            </div>
            <div class="auth-popup-title">
                Особистий кабінет
            </div>
{{--            <small>Логин и пароль это Ваш номер телефона в формате 3801234567</small>--}}
            <form class="auth-popup-form"  role="form" action="{{ route('login.client') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="field">
                    <label class="input-label">
                        Телефон:
                    </label>
                    <input type="text" name="phone" id="phone" class="input phone-mask" placeholder=""  required>
                    @if ($errors->has('phone'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="field">
                    <label class="input-label">
                        Пароль: <br><small>* за замовчуванням Ваш номер телефону у форматі 380...</small>
                    </label>
                    <input type="password" id="password" name="password" class="input" placeholder="" required>

                </div>
                <button type="submit">
                    Увійти
                </button>
            </form>
            <div class="manager-feedback">
                <button class="nav-btn" type="button" data-toggle="modal" data-target="#diagnosticModal">
                    Записатися на розвал-сходження
                </button>
            </div>
            <div class="manager-feedback">
                <button type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModal">
                    Підтримка!
                </button>
            </div>
        </div>

    </section>
</div>
<footer>
@include('includes.__no_authorization')
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Просто напишіть своє запитання!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                        <form action="{{route('manager.connect')}}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-group">
                                <label for="exampleInputEmail1">Номер телефону</label>
                                <input type="tel" class="form-control" id="InputPhone" name="phone"  placeholder="380 (__) ___ __ __">
                            </div>
                        <div class="form-group">
                            <label for="question">Питання</label>
                            <textarea class="form-control" name="question" id="question" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
                        <button type="submit" class="btn btn-success">Надіслати</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</footer>
<script type="text/javascript" src="{{asset('js/scripts.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script type="text/javascript" src="{{asset('js/custom.js?v=3.2')}}"></script>
<script>
    $('.phone-mask').mask('380000000000', {placeholder: ""});
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-PB4W2G0B4B"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-PB4W2G0B4B');
</script>
</body>
</html>