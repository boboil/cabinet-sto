{{-- Extends layout --}}
@extends('layouts.default')

{{-- Content --}}
@section('content')

    <main id="all_works">
        <section class="block-all-works">
            <div class="wrap">
                <h1 class="block-title">
                    Вся історія
                </h1>
                <small>Введіть часткову назву роботи або деталі та відобразиться вся історія, пов'язана з цією
                    назвою
                </small>
                <div class="block-head-controls">
                    <div class="type-selector" style="margin-bottom: 10px;">
                        <select id="select_car" onchange="selectCar();">
                            <option value="{{$car->ID}}" selected
                                    disabled hidden>{{$car->RegistrationNo}} {{$car->Brand}} {{$car->Model}}</option>
                            @foreach($cars as $car)
                                <option value="{{$car->ID}}">{{$car->RegistrationNo}} {{$car->Brand}} {{$car->Model}}</option>
                            @endforeach
                        </select>
                    </div>
                    <form class="search" id="search_form" method="post" action="#">
                        <input id="search" type="text" placeholder="Пошук..."
                               value="@if(isset($search)){{$search}}@endif">
                        <button type="submit"></button>
                    </form>
                </div>

                <div class="all-works-table">
                    <div class="table-head">
                        <div class="head-column-group">
                            <div class="head-column">
                                Дата/Пробіг
                            </div>
                            <div class="head-column">
                                Назва
                            </div>
                        </div>
                        <div class="head-column">
                            Кількість замін
                        </div>
                    </div>
                    <div class="table-body">
                        @if($works->isNotEmpty() || $products->isNotEmpty())
                            @foreach($works as $work)
                                <div class="work-item">
                                    <div class="work-item-content">
                                        @foreach($work->sortByDesc('CarOdometer') as $item)
                                            <div class="work-date-distance {{$loop->iteration == 1 ? 'first-row' : ''}}"
                                                 @if(session('admin') == 1)
                                                 data-id="{{$item->ID}}"
                                                 data-type="work"
                                                 onclick="showModal(this)"
                                                    @endif
                                            >
                                                @if($item->Variant == 'W')
                                                    <span style="color:blue">
                                                        <b>Виконано</b>  <i class="fas fa-tools"></i>
                                                    </span>
                                                @else
                                                    <span style="color:green">
                                                        <b>Деталь</b> <i class="fa fa-cog" aria-hidden="true"></i>
                                                    </span>
                                                @endif
                                                <span>
                                                    {{$item->Date->format('d-m-Y')}}
                                                </span>
                                                <span>
                                                    {{$item->CarOdometer}} км
                                                </span>
                                                @if($car->ID == 0)
                                                    <span>{{$item->CarName}}</span>
                                                @endif
                                            </div>
                                            <div class="work-name {{$loop->iteration == 1 ? 'first-row' : ''}}">
                                                @if($loop->iteration == 1)
                                                    {{$item->Name}}
                                                @endif
                                            </div>

                                        @endforeach
                                    </div>
                                    <div class="work-item-progress">
                                        <!-- <span>Всего замен: </span> -->
                                        <b>{{count($work)}}</b>
                                    </div>

                                    @if(count($work) > 1)
                                        <button>
                                            Раніше
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                            @foreach($products as $product)
                                <div class="work-item">
                                    <div class="work-item-content">
                                        @foreach($product->sortByDesc('CarOdometer') as $item)
                                            <div class="work-date-distance {{$loop->iteration == 1 ? 'first-row' : ''}}"
                                                 @if(session('admin') == 1)
                                                 data-id="{{$item->ID}}"
                                                 data-type="product"
                                                 onclick="showModal(this)"
                                                    @endif
                                            >
                                                    <span style="color:green">
                                                        <b>Деталь</b> <i class="fa fa-cog" aria-hidden="true"></i>
                                                    </span>
                                                <span>
                                                    {{$item->Date->format('d-m-Y')}}
                                                </span>
                                                <span>
                                                    {{$item->CarOdometer}} км
                                                </span>
                                                @if($car->ID == 0)
                                                    <span>{{$item->CarName}}</span>
                                                @endif
                                            </div>
                                            <div class="work-name {{$loop->iteration == 1 ? 'first-row' : ''}}">
                                                @if($loop->iteration == 1)
                                                    {{$item->Name}}
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="work-item-progress">
                                        <!-- <span>Всего замен: </span> -->
                                        <b>{{count($product)}}</b>
                                    </div>
                                    @if(count($product) > 1)
                                        <button>
                                            Раніше
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                            @if(!$full)
                                <button type="button" class="btn btn-info show-more" id="showAllRec"
                                        onclick="loadAllHistory()">
                                    Показати всю історію
                                </button>
                            @endif
                        @else
                            @if($is_full)
                                <div class="work-item">
                                    <div class="work-item-content">
                                        <div class="work-date-distance first-row"></div>
                                        <div class="work-name first-row">
                                            Немає результатів за останній рік
                                        </div>
                                        <div class="work-item-progress first-row">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-info show-more" id="showAllRec"
                                        onclick="loadAllHistory()">
                                    Показати всю історію
                                </button>
                            @else
                                <div class="work-item">
                                    <div class="work-item-content">
                                        <div class="work-date-distance first-row"></div>
                                        <div class="work-name first-row">
                                            Немає результатів
                                        </div>
                                        <div class="work-item-progress first-row"></div>
                                    </div>
                                </div>
                            @endif
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
            $("#all_works").load("/index-jobs-selected", {selected: selected}, function () {
                $(".preloader").css('display', 'none');
            });
        }

        function loadAllHistory() {
            let selected = document.getElementById('select_car');
            $("#all_works").load("{{route('all.jobs.full')}}", {selected: selected.value}, function () {
                $(".preloader").css('display', 'none');
            });
        }

        $("#search_form").submit(function (event) {
            event.preventDefault();
            let selected = $("#select_car option:selected").val(),
                search = $('#search').val();
            $(".preloader").css('display', 'block');
            $("#all_works").load("/search-jobs", {selected: selected, search: search}, function () {
                $(".preloader").css('display', 'none');
            });
        });
    </script>
    @if(session('admin') == 1)
        <script>
            function showModal(elem) {
                let id = elem.getAttribute('data-id'),
                    type = elem.getAttribute('data-type');
                // modal = $('#adminModal');
                $.ajax({
                    method: "POST",
                    url: "/load-admin-modal",
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
