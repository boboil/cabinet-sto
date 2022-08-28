{{-- Extends layout --}}
@extends('layouts.default')

{{-- Content --}}
@section('content')
    <main id="acts_works">
        <section class="block-work-acts">
            <div class="wrap">
                <h1 class="block-title">
                    Акти виконаних робіт
                </h1>
                <div class="block-head-controls">
                    <!-- <form class="search">
                        <input type="text" placeholder="Поиск...">
                        <button type="submit"></button>
                    </form> -->
                    <div class="type-selector">
                        <select id="select_car" onchange="selectCar();">
                            <option value="{{$car->ID}}" selected disabled>{{$car->RegistrationNo}} &#x20;&#x20;  {{$car->Brand}} {{$car->Model}}</option>
                            @foreach($cars as $car)
                                <option value="{{$car->ID}}">{{$car->RegistrationNo}}  &#x20;&#x20; {{$car->Brand}} {{$car->Model}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="work-acts-chronology">
                    @foreach($group as $key => $item)
                        <div class="work-acts-year">
                            <div class="work-acts-year-title">
                                {{$key}}
                            </div>
                            <div class="work-acts-year-list">
                                <div class="list-inner">
                                    @foreach($item as  $act)
                                            <a href="{{route('acts', [$act->orderId, $act->RecType])}}" class="act-item">
                                                <div class="subtitle">
                                                   <b style="color:red">{{$act->status}}</b>
                                                </div>
                                                <div class="value">
                                                    
                                                </div>
                                                <div class="subtitle">
                                                    Дата:
                                                </div>
                                                <div class="value">
                                                    {{$act->date}}
                                                </div>
                                                <div class="subtitle">
                                                    Пробіг:
                                                </div>
                                                <div class="value">
                                                    {{$act->CarOdometer}} км
                                                </div>
                                                <div class="subtitle">
                                                    Авто:
                                                </div>
                                                <div class="value">
                                                    {{$act->CarName}}
                                                </div>
                                            </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
</div>
@endsection


@section('styles')

@endsection

@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function selectCar() {
            let selected = $('#select_car').val();
            console.log(selected);
            if(selected === "0"){
                $(".preloader").css('display', 'block');
                $( "#acts_works" ).load( "/index-acts", function() {
                    $(".preloader").css('display', 'none');
                });
            }else{
                $(".preloader").css('display', 'block');
                $( "#acts_works" ).load( "/index-acts-selected", { selected: selected }, function() {
                    $(".preloader").css('display', 'none');
                });
            }
        }
    </script>
@endsection
