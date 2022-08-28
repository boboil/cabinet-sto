<form action="{{route('add.diagnostic.order')}}" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    <div class="modal-body">
        <div class="block-head-controls">
            <select name="car" class="form-control" required>
                <option value="0" selected disabled hidden>Виберіть автомобіль</option>
                @foreach(session()->get('cars') as $car)
                    <option value="{{$car->ID}}">{{$car->RegistrationNo}} > {{$car->Brand}} {{$car->Model}}</option>
                @endforeach
            </select>
            <div class="form-check" style="margin-top: 10px">
                <div>
                    <label class="btn btn-secondary" for="today">Сьогодні</label>
                    <input type="radio" class="btn-check" name="day" onchange="syncTime('today');" value="today"
                           id="today" autocomplete="off">
                </div>
                <div>
                    <label class="btn btn-secondary" for="tomorrow">Завтра</label>
                    <input type="radio" class="btn-check" name="day" onchange="syncTime('tomorrow');"
                           value="tomorrow" id="tomorrow" autocomplete="off">
                </div>

            </div>
            <div class="form-group">
                <label for="question">Час</label>
                <select class="form-control" name="time" id="time" required>
                    <option value="" disabled selected hidden>Оберіть час</option>
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