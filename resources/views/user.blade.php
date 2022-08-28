@extends('layouts.default')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        {{--<ol class="breadcrumb page-breadcrumb">--}}
            {{--<li class="breadcrumb-item"><a href="javascript:void(0);">SmartAdmin</a></li>--}}
            {{--<li class="breadcrumb-item">Datatables</li>--}}
            {{--<li class="breadcrumb-item active">ColumnFilter</li>--}}
            {{--<li class="position-absolute pos-top pos-right d-none d-sm-block"><span class="js-get-date"></span></li>--}}
        {{--</ol>--}}
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-table'></i> Таблица: <span class='fw-300'>Заказов</span> <sup class='badge badge-primary fw-500'>ADDON</sup>
                {{--<small>--}}
                    {{--Create headache free searching, sorting and pagination tables without any complex configuration--}}
                {{--</small>--}}
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                          Роботы<span class="fw-300"><i></i></span>
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                            <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                            <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="dt-basic-example" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-highlight">
                                <tr>
                                    <th>OrderID</th>
                                    <th>No</th>
                                    <th>Дата</th>
                                    <th>Статус</th>
                                    <th>DocCode</th>
                                    <th>Оплата</th>
                                    <th>Оплачено</th>
                                    <th>Сумма заказа</th>
                                    <th>Вид рабы</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{$order->ID}}</td>
                                        <td>{{$order->No}}</td>
                                        <td>{{\Carbon\Carbon::parse($order->Date)->format('d-m-Y')}}</td>
                                        <td>{{$order->StatusCode}}</td>
                                        <td>{{$order->DocCode}}</td>
                                        <td>{{$order->IsPaid}}</td>
                                        <td>{{$order->Payment}} {{$order->Currency}}</td>
                                        <td>{{$order->Total}} {{$order->Currency}}</td>
                                        <td>{{$order->Notes}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>OrderID</th>
                                    <th>No</th>
                                    <th>Дата</th>
                                    <th>Статус</th>
                                    <th>DocCode</th>
                                    <th>Оплата</th>
                                    <th>Оплачено</th>
                                    <th>Сумма заказа</th>
                                    <th>Вид рабы</th>
                                </tr>
                                </tfoot>
                            </table>
                            <!-- datatable end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
