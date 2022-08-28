<form action="{{route('add.google.diagnostic.order')}}" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    <div class="modal-body">
        <div class="block-head-controls">
            <select name="car" class="form-control" onchange="showTextInput();" id="google_calendar_car_select">
                <option value="0" selected>Виберіть автомобіль</option>
                @foreach(session()->get('cars') as $car)
                    <option value="{{$car->ID}}">{{$car->RegistrationNo}} > {{$car->Brand}} {{$car->Model}}</option>
                @endforeach
                <option value="another">Інший автомобіль</option>
            </select>
            <input type="text" class="form-control" id="google_calendar_car_input" name="another_car"
                   placeholder="Введіть модель та рік авто" style="display: none; margin-top: 10px">

            <div class="form-check" style="margin-top: 10px">
                <div>
                    {{--<label class="form-check-label" for="todayG">Сегодня</label>--}}
                    {{--<input type="radio" class="form-check-input" name="day" onchange="syncTimeGoogle('today');"--}}
                    {{--value="today" id="todayG">--}}
                    <input type="radio" class="btn-check" name="day" id="todayG" autocomplete="off"
                           onchange="syncTimeGoogle('today');" value="today">
                    <label class="btn btn-secondary" for="todayG">Сьогодні</label>
                </div>
                <div>
                    <input type="radio" class="btn-check" name="day" id="tomorrowG" autocomplete="off"
                           onchange="syncTimeGoogle('tomorrow');" value="tomorrow">
                    <label class="btn btn-secondary" for="tomorrowG">Завтра</label>

                    {{--<label class="form-check-label" for="tomorrowG">Завтра</label>--}}
                    {{--<input type="radio" class="form-check-input" name="day" onchange="syncTimeGoogle('tomorrow');"--}}
                    {{--value="tomorrow" id="tomorrowG">--}}
                </div>
            </div>
            <div class="form-group">
                <label for="question">Час</label>
                {{--<input type="time" class="form-control" name="time" list="googleTimeList" min="10:00" max="16:00" id="googleTime" step="3600" disabled>--}}
                <select class="form-control" name="time" id="googleTime" disabled>
                    <option value="10:00">10:00</option>
                </select>
                <label for="question">Опишіть коротко почну звернення, свій біль:)</label>
                <textarea class="form-control" name="question" id="question" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
        <button type="submit" class="btn btn-success">Записатись</button>
    </div>
</form>