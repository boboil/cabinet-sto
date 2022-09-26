{{-- Extends layout --}}
@extends('layouts.default')

{{-- Content --}}
@section('content')

    <main id="all_works">
        <section class="block-all-works">
            <div class="wrap">
                <h1 class="block-title">
                    Рекомендації
                </h1>
                <div class="block-head-controls">
                    <div class="type-selector" style="margin-bottom: 10px;">
                        <select id="select_car" onchange="selectCar();">
                            <option value="{{$car->ID}}" selected
                                    disabled hidden>{{$car->RegistrationNo}} {{$car->Brand}} {{$car->Model}}</option>
                            @foreach($cars as $carr)
                                <option value="{{$carr->ID}}">{{$carr->RegistrationNo}} {{$carr->Brand}} {{$carr->Model}}</option>
                            @endforeach
                        </select>
                    </div>
{{--                    <form class="search" id="search_form" method="post" action="#">--}}
{{--                        <input id="search" type="text" placeholder="Пошук..."--}}
{{--                               value="@if(isset($search)){{$search}}@endif">--}}
{{--                        <button type="submit"></button>--}}
{{--                    </form>--}}
                </div>

                <div class="all-works-table">
                    <div class="table-head">
                        <div class="head-column">
                            Дата/Пробіг
                        </div>
                        <div class="head-column">
                            <b style="color: red">Рекомендуємо</b>
                        </div>
                    </div>
                    <div class="table-body">
                        @if($works->isNotEmpty())
                            <div class="work-item" style="display: flex">
                                <div class="work-item-content">
                                    @foreach($works as $item)
                                        <div class="work-date-distance first-row">
                                            <b>{{$item->Date->format('d-m-Y')}}</b>
                                            <span>{{$item->CarOdometer}} км</span>
                                            @if($car->ID == 0)
                                                <span>{{$item->CarName}}</span>
                                            @endif
                                        </div>
                                        <div class="work-name first-row">
                                            {{$item->Name}}
                                            @if($item->Notes)
                                                <br>Примітка: <small>{{$item->Notes}}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <button type="button" class="btn btn-info show-more" id="showAllRec"
                                    onclick="showAllRec();">
                                Показати всі
                            </button>
                        @else
                            <div class="block-no-result">За останні 3 місяці рекомендацій немає</div>
                            <button type="button" class="btn btn-info show-more" id="showAllRec"
                                    onclick="showAllRec();">
                                Показати всі
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection

{{-- Styles Section --}}
@section('styles')

@endsection


{{-- Scripts Section --}}
@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function selectCar() {
            let selected = $('#select_car').val();
            $(".preloader").css('display', 'block');
            $("#all_works").load("/index-recomendation-selected", {selected: selected}, function () {
                $(".preloader").css('display', 'none');
            });
        }

        $("#search_form").submit(function (event) {
            event.preventDefault();
            let selected = $("#select_car option:selected").val(),
                search = $('#search').val();
            $(".preloader").css('display', 'block');
            $("#all_works").load("/search-recomendation", {selected: selected, search: search}, function () {
                $(".preloader").css('display', 'none');
            });
        });

        function showAllRec() {
            let selected = $("#select_car option:selected").val();
            $(".preloader").css('display', 'block');
            $("#all_works").load("/recommendation-all", {selected: selected}, function () {
                $(".preloader").css('display', 'none');
                $('#showAllRec').css('display', 'none');
            });
        }
    </script>
    @if(session('admin') == 1)
        <script>
            function showModal(elem) {
                let id = elem.getAttribute('data-id'),
                    type = elem.getAttribute('data-type');
                $.ajax({
                    method: "POST",
                    url: "/load-admin-modal-rec",
                    data: {id: id, type: type}
                })
                    .done(function (data) {
                        console.log(data.Description);
                        $('#adminModal > div > div > div.modal-body > div.description > span').html(data.Description);
                        $('#adminModal > div > div > div.modal-body > div.StdHour > span').html(data.StdHour);
                        $('#adminModal > div > div > div.modal-body > div.Total > span').html(data.Total);
                        $('#adminModal > div > div > div.modal-body > div.WorkerName > span').html(data.WorkerName);
                        $('#adminModal').modal('show');
                    });

            }

        </script>
    @endif
@endsection
@section('modal')
    @if(session('admin') == 1)
        <div class="modal fade" id="adminModal" tabindex="-1" role="dialog" aria-labelledby="adminModal"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adminModalLabel">Додаткові відомості!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="description">Опис: <span>Description</span></div>
                        <div class="StdHour">Години роботи: <span>Description</span></div>
                        <div class="Total">Ціна: <span>Description</span></div>
                        <div class="WorkerName">Виконавець: <span>Description</span></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
