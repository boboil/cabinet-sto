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
            <div class="modal-body">
                <div class="block-head-controls">
                    <label for="selectDiagnostic" class="text-center"><b>Що хочеться? :)</b></label>
                    <select class="form-control" name="" id="selectDiagnostic" onchange="selectDiagnostic();">
                        <option value="#placeholder" selected>Виберіть тип</option>
                        <option value="#diagnostic">Діагностика</option>
                        <option value="#camberToe">Розвал-сходження</option>
                    </select>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="placeholder">

                </div>
                <div class="tab-pane fade" id="diagnostic">
                    @include('includes.__diagnostic')
                </div>
                <div class="tab-pane fade" id="camberToe">
                    @include('includes.__google_calendar_online')
                </div>
            </div>
        </div>
    </div>
</div>