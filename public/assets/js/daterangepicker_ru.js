    $('input[id="pick_period2"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: false,
        minDate: moment(today).add(1, 'days'),
        maxDate: moment(today).add(45, 'days'),
      //add locale to your daterangepicker call function
        locale: {
            format: 'DD-MM-YYYY',
            applyLabel: 'Принять',
            cancelLabel: 'Отмена',
            invalidDateLabel: 'Выберите дату',
            daysOfWeek: ['Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс', 'Пн'],
            monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            firstDay: 1
        }
    });