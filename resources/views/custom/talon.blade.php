@extends('layouts.default')

@section('content')
    <main id="acts_works">
        <section class="block-work-acts">
            <div class="wrap">
                <h1 class="block-title">
                    Талони
                </h1>

                {{$prWorks}}

{{--                <div class="work-acts-chronology">--}}
{{--                    @foreach($group as $key => $item)--}}
{{--                        <div class="work-acts-year">--}}
{{--                            @if($key == '* ТАЛОНЫ * ')--}}
{{--                            <div class="work-acts-year-list">--}}
{{--                                <div class="list-inner">--}}
{{--                                    @foreach($item as  $act)--}}
{{--                                        <a href="{{route('acts.talon', [$act->orderId, $act->RecType])}}" class="act-item">--}}
{{--                                            <div class="subtitle">--}}
{{--                                                <b style="color:red">{{$act->status}}</b>--}}
{{--                                            </div>--}}
{{--                                            <div class="value">--}}

{{--                                            </div>--}}
{{--                                            <div class="subtitle">--}}
{{--                                                Дата:--}}
{{--                                            </div>--}}
{{--                                            <div class="value">--}}
{{--                                                {{$act->date}}--}}
{{--                                            </div>--}}
{{--                                            <div class="subtitle">--}}
{{--                                                Пробег:--}}
{{--                                            </div>--}}
{{--                                            <div class="value">--}}
{{--                                                {{$act->CarOdometer}} км--}}
{{--                                            </div>--}}
{{--                                            <div class="subtitle">--}}
{{--                                                Авто:--}}
{{--                                            </div>--}}
{{--                                            <div class="value">--}}
{{--                                                {{$act->CarName}}--}}
{{--                                            </div>--}}
{{--                                        </a>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            @elseif($key == '* ТАЛОНЫ * ')--}}
{{--                                <p>У вас немає талонів</p>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
            </div>
        </section>
    </main>
    </div>
@endsection

