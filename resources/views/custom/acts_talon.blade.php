{{-- Extends layout --}}
@extends('layouts.default')

{{-- Content --}}
@section('content')
    <main>
        <section class="block-one-act">
            <div class="wrap">
                <h1 class="block-title">
                    АКТ №{{$data['actId']}} от {{$data['date']}}
                </h1>
                <div class="act-head">
                    <div class="act-head-group">
                        <div class="act-head-group-title">
                            Автомобиль / <b>Пробег, км</b>
                        </div>
                        <div class="act-head-group-value">
                            {{$data['CarName']}} / <b>{{$data['CarOdometer']}} км</b>
                        </div>
                    </div>
                    @if($user->Email != "0")
                        <div class="act-head-group">
                            <div class="act-head-group-title">
                                Сумма всего:
                            </div>
                            <div class="act-head-group-value">
                                {{ $data['woks']->sum('Total') +  $data['products']->sum('Total')}} грн
                            </div>
                            @if($recommendations['woks']->isNotEmpty())
                                <div class="act-head-group-title">
                                    Предварительня сумма по рекомендациям:
                                </div>
                                <div class="act-head-group-value">
                                    {{$recommendations['sumRec']}} грн
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                @if($data['woks']->where('reason', 1)->isNotEmpty())
                    <div class="act-category">
                        <div class="act-category-title">
                            Причина обращения от клиента
                        </div>
                        <div class="act-category-content">
                            <table class="act-category-table">
                                <thead>
                                <tr>
                                    <th>
                                        <!--Название-->
                                    </th>
                                    <th>
                                    Часы работы
                                    </th>
                                    <th>
                                    Сумма
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data['woks']->where('reason', 1) as $datum)
                                    <tr>
                                        <td>
                                            {!! $datum->Notes !!}
                                        </td>
                                        <td>
                                        {{$datum->StdHour}}
                                        </td>
                                        <td>
                                        {{$datum->Total}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                @if($data['woks']->where('reason', 0)->isNotEmpty())
                    <div class="act-category">
                        <div class="act-category-title">
                            Работы
                        </div>
                        <div class="act-category-content">
                            <table class="act-category-table">
                                <thead>
                                <tr>
                                    <th>
                                        Название
                                    </th>
                                    <th>
                                        Кол-во
                                    </th>
                                    <th>
                                    Сумма
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data['woks']->where('reason', 0) as $datum)
                                    <tr>
                                        <td>
                                            {{$datum->Name}}
                                        </td>
                                        <td>
                                            {{$datum->Quantity}}
                                        </td>
                                        <td>
                                        {{$datum->Total}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                @if($data['products']->isNotEmpty())
                    <div class="act-category">
                        <div class="act-category-title">
                            Запчасти
                        </div>
                        <div class="act-category-content">
                            <table class="act-category-table">
                                <thead>
                                <tr>
                                    <th>
                                        Название
                                    </th>
                                    <th>
                                        Кол-во
                                    </th>
                                    <th>
                                    Сумма
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data['products'] as $datum)
                                    <tr>
                                        <td>
                                            {{$datum->Name}}
                                        </td>
                                        <td>
                                            {{$datum->Quantity}}
                                        </td>
                                        <td>
                                        {{$datum->Total}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                @if($recommendations['woks']->isNotEmpty())
                    <div class="act-category">
                        <div class="act-category-title" style="color:red;">
                            Дефектовано
                        </div>
                        <div class="act-category-content">
                            <table class="act-category-table">
                                <thead>
                                <tr>
                                    <th>
                                        Название
                                    </th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recommendations['woks'] as $recommendation)
                                    @if(!$recommendation->Notes)
                                        <tr>
                                            <td>
                                                {{$recommendation->Name}}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                @if($recommendations['woks']->isNotEmpty())
                    @if($recommendations['woks']->where('ID', 1767)->first())
                        <div class="act-category">
                            <div class="act-category-title" style="color:#0014ff;">
                                Вот что ещё хотелось добавить
                            </div>
                            <div class="act-category-content">
                                <table class="act-category-table">
                                    <thead>
                                    <tr>
                                        <th>

                                        </th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($recommendations['woks'] as $recommendation)
                                        @if($recommendation->Notes)
                                            <tr>
                                                <td style="font-size: 15px;">
                                                    @if($recommendation->Notes)
                                                        <span>{{$recommendation->Notes}}</span>@endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endif
{{--                <div class="navigation-controls">--}}
{{--                    <button class="nav-btn prev" onclick="window.location.href = '{{route('talon')}}'">--}}
{{--                        Назад--}}
{{--                    </button>--}}
{{--                    @if(isset($next) && !empty($next))--}}
{{--                        <button class="nav-btn next"--}}
{{--                                onclick="window.location.href = '{{route('actstalon', [$next->ID, $next->RecType])}}'">--}}
{{--                            Следующий--}}
{{--                        </button>--}}
{{--                    @endif--}}
{{--                </div>--}}
            </div>
        </section>
    </main>
@endsection

