<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gold Auto</title>
    <link rel="stylesheet" type="text/css" href="{{asset('css/styles.css')}}">

    <!-- <link rel="icon" type="image/png" href="favicon.png"> -->

</head>

<body>
<div class="wrapper">
    <section class="block-auth">
        <div class="auth-popup">
            <div class="auth-popup-logo">
                <img src="{{asset('img/logo.png')}}" alt="" width="72">
            </div>
            <div class="auth-popup-title">
                Вход в аккаунт

            </div>
            <small>Логин и пароль это Ваш номер телефона</small>
            <form class="auth-popup-form" action="{{route('login')}}" method="post">
                @csrf
                <div class="field">
                    <label class="input-label">
                        Логин
                    </label>
                    <input type="text" name="phone" class="input phone-mask" placeholder="380 (__) ___ __ __">
                </div>
                <div class="field">
                    <label class="input-label">
                        Пароль
                    </label>
                    <input type="password" name="password" class="input" placeholder="Введите пароль">

                </div>
                <button type="submit">
                    Войти
                </button>

            </form>
        </div>
    </section>
</div>

<script type="text/javascript" src="{{asset('js/scripts.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $('.phone-mask').mask('380000000000', {placeholder: "38(___)-___-__-__"});
</script>
</body>

</html>