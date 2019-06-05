$(document).ready(function () {
    var actionsBtnGroup = $('.actions'),
            from_text_label = $('.from-text'),
            to_text_label = $('.to-text'),
            from_hidden_field = $('#from-hidden'),
            to_hidden_field = $('#to-hidden'),
            fromDatePicker = $('#from'),
            toDatePicker = $('#to'),
            dateFormat = 'mm-dd-yy',
            from = $("#input1"),
            to = $("#input2");


    fromDatePicker.datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        showButtonPanel: false,
        minDate: null,
        numberOfMonths: 1,
        dateFormat: dateFormat,
        onSelect: function (dateText, inst) {
            from.val(dateText);
//        toDatePicker.datepicker("option", "minDate", dateText);
        },
        onChangeMonthYear: function (year, month, inst) {
            var month = inst.selectedMonth < 10 ? '0' + inst.selectedMonth : inst.selectedMonth;
            var day = inst.selectedDay < 10 ? '0' + inst.selectedDay : inst.selectedDay;
            from.val(month + '-' + inst.selectedDay + '-' + inst.selectedYear);
        }
    });
    toDatePicker.datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        showButtonPanel: false,
        minDate: null,
        numberOfMonths: 1,
        dateFormat: dateFormat,
        onSelect: function (dateText, inst) {
            to.val(dateText);
//        fromDatePicker.datepicker("option", "maxDate", dateText);
        },
        onChangeMonthYear: function (year, month, inst) {
            var month = inst.selectedMonth < 10 ? '0' + inst.selectedMonth : inst.selectedMonth;
            var day = inst.selectedDay < 10 ? '0' + inst.selectedDay : inst.selectedDay;
            to.val(month + '-' + inst.selectedDay + '-' + inst.selectedYear);
        }
    });
    $('.select-data-range-btn').on('click', function (event) {
        event.preventDefault();
        $('#calendarModal').modal('show');
    });
    $('.apply-date-range').on('click', function () {
        var from_text = $('#input1').val();
        var to_text = $('#input2').val();
        if (!from_text) {
            sweetAlert("Oops...", "Start Date can\'t be blank. Please select a date", "error");
            return false;
        }
        if (!to_text) {
            sweetAlert("Oops...", "End Date can\'t be blank. Please select a date", "error");
            return false;
        }

        from_text_label.text(from_text);
        to_text_label.text(to_text);
        //
        from_hidden_field.val(from_text);
        to_hidden_field.val(to_text);
        //
        $('#calendarModal').modal('hide');
        $('.step2').show();
        $('.date-labels').show();
    });
    $("#source-file").change(function () {
        if ($(this).val()) {
            $('.step3').show();
            if ($('#label-file').val()) {
                $('.actions').show();
            }
        } else {
            $('.actions').hide();
        }
    });
    $('#label-file').keyup(function () {
        if ($(this).val()) {
            actionsBtnGroup.show();
        } else {
            actionsBtnGroup.hide();
        }
    });
});