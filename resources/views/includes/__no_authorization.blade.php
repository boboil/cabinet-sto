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
            <form action="{{route('manager.connect')}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="modal-body">
                    <div class="form-group">
                        <label for="InputPhone">Номер телефону</label>
                        <input type="tel" class="form-control" id="InputPhone" name="phone"  required>
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

<div class="modal fade" id="diagnosticModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Онлайн запис!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add.google.diagnostic.order.noAuthorization')}}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="modal-body">
                    <div class="block-head-controls">
                        <input type="text" class="form-control phone-mask" id="google_calendar_phone_input" name="phone"
                               placeholder="Введіть номер телефону" style=" margin-top: 10px" required>
                        <input type="text" class="form-control" id="google_calendar_car_input" name="another_car"
                               placeholder="Введіть модель та рік авто" style=" margin-top: 10px" required maxlength="100">


                        <div class="form-check" style="margin-top: 10px">
                            <div>
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
                            <select class="form-control" name="time" id="googleTime" required>
                                <option value="" disabled selected hidden>Оберіть час</option>
                            </select>
                            <label for="question">Коментар</label>
                            <textarea class="form-control" name="question" id="question" rows="3" maxlength="200"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
                    <button type="submit" class="btn btn-success">Записатись</button>
                </div>
            </form>
        </div>
    </div>
</div>