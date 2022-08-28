$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function updateData() {
    $.ajax({
        method: "GET",
        url: "/update-all-data",
        beforeSend: function (xhr) {
            $(".preloader").css('display', 'block');
        }
    })
        .done(function () {
            $(".preloader").css('display', 'none');
            window.location.reload();
        });
}

function showTextInput() {
    let val = document.getElementById("google_calendar_car_select").value;
    if (val == 'another') {
        $("#google_calendar_car_input").css('display', 'block');
    } else {
        $("#google_calendar_car_input").css('display', 'none').css('margin-top', '15px');

        document.getElementById("google_calendar_car_input").value = '';
    }
    console.log(val);
}

function syncTime(day) {
    $.ajax({
        method: "POST",
        url: "/check-available-time",
        data: {day: day, _token: $('meta[name="csrf-token"]').attr('content')}
    }).done(function (data) {
        let dataList = '';
        for (let prop in data) {
            dataList += "<option value='" + data[prop] + "'>" + data[prop] + "</option>";
        }
        $('#time').empty().append(dataList);
        if (dataList.length) {
            $('#time').attr('disabled', false);
        } else {
            dataList = `<option value='' disabled selected>Немає вільного часу</option>`;
            $('#time').empty().append(dataList);
        }

    });
}

function syncTimeGoogle(day) {
    $.ajax({
        method: "POST",
        url: "/google-check-available-time",
        data: {day: day, _token: $('meta[name="csrf-token"]').attr('content')}
    }).done(function (data) {
        let dataList = '';
        for (let prop in data) {
            dataList += "<option value='" + data[prop] + "'>" + data[prop] + "</option>";
        }
        $('#googleTime').empty().append(dataList);
        if (dataList.length) {
            $('#googleTime').attr('disabled', false);
        } else {
            dataList = `<option value='' disabled selected hidden>Немає вільного часу</option>`;
            $('#googleTime').empty().append(dataList);
        }

    });
}
function selectDiagnostic() {
    let modal;
    modal = $('#selectDiagnostic').val();
    console.log(modal);
    $(modal).addClass('show active')
        .siblings()
        .removeClass('show active');
}
