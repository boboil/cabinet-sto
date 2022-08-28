<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gold Auto</title>
    <link rel="stylesheet" type="text/css" href="{{asset('css/styles.css')}}">
    <link rel="stylesheet" media="screen, print" href="{{asset('css/custom.css?v=2')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
                Вхід до облікового запису

            </div>
            <small>Логін та пароль це Ваш номер телефону у форматі 380...</small>
            <form class="auth-popup-form"  role="form" action="{{ url('/login') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="field">
                    <label class="input-label">
                        Логін
                    </label>
                    <input type="text" name="phone" id="phone" class="input phone-mask" placeholder="380 (__) ___ __ __" required>
                    @if ($errors->has('phone'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="field">
                    <label class="input-label">
                        Пароль
                    </label>
                    <input type="password" id="password" name="password" class="input" placeholder="Введіть пароль" required>

                </div>
                <button type="submit">
                    Увійти
                </button>
            </form>
            {{--<div class="manager-feedback">--}}
                {{--<button type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModal">--}}
                    {{--Обратиться к менеджеру!--}}
                {{--</button>--}}
            {{--</div>--}}
        </div>

    </section>
</div>
<footer>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    $('.phone-mask').mask('380000000000', {placeholder: "38(___)-___-__-__"});
</script>
</body>

</html>