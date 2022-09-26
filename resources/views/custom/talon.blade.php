{{-- Extends layout --}}
@extends('layouts.default')

{{-- Content --}}
@section('content')
    <main id="acts_works">
        <section class="block-work-acts">
            <div class="wrap">
                <h1 class="block-title">
                    Мої талони
                </h1>
                <div class="work-acts-chronology">
                    @foreach($unusedTickets as $key => $YearTickets)
                        <div class="work-acts-year">
                            <div class="work-acts-year-title">
                                {{$key}}
                            </div>
                            <div class="work-acts-year-list">
                                <div class="list-inner">
                                    @foreach($YearTickets as  $ticket)
                                        <a href="#" class="act-item">
                                            <div class="subtitle">
                                                @if($ticket->usage)
                                                    <b style="color:red">Використано</b>
                                                @endif
                                            </div>
                                            <div class="value">

                                            </div>
                                            <div class="value">
                                                {{$ticket->Name}}
                                            </div>
                                            <div class="subtitle">
                                                Дата:
                                            </div>
                                            <div class="value">
                                                {{\Carbon\Carbon::parse($ticket->Date)->format('d-m-Y')}}
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                </div>
                <div class="work-acts-chronology">
                    @foreach($usedTickets as $key => $YearTickets)
                        <div class="work-acts-year">
                            <div class="work-acts-year-title">
                                {{$key}}
                            </div>
                            <div class="work-acts-year-list">
                                <div class="list-inner">
                                    @foreach($YearTickets as  $ticket)
                                        <a href="#" class="act-item">
                                            <div class="subtitle">
                                                @if($ticket->usage)
                                                    <b style="color:red">Використано</b>
                                                @endif
                                            </div>
                                            <div class="value">

                                            </div>
                                            {{--<div class="subtitle">--}}
                                            {{--Назва:--}}
                                            {{--</div>--}}
                                            <div class="value">
                                                {{$ticket->Name}}
                                            </div>
                                            <div class="subtitle">
                                                Дата:
                                            </div>
                                            <div class="value">
                                                {{\Carbon\Carbon::parse($ticket->Date)->format('d-m-Y')}}
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                </div>
            </div>
        </section>
    </main>
    </div>
@endsection


@section('styles')

@endsection

@section('scripts')

@endsection
