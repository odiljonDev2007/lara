<!DOCTYPE html>
<html>

<head>
    <title>How to Use Fullcalendar in Laravel 8</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    
    <!--  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.13/locales-all.global.min.js'></script>-->
</head>

<!DOCTYPE html>
<html>



<body>

    <div class="container">

        <div id="calendar"></div>

        <!-- Modal -->
        <div class="modal" id="eventModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Add Event</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="eventForm">
                            <div class="form-group">
                                <label for="eventTitle">Title:</label>
                                <input type="text" class="form-control" id="eventTitle" required>
                            </div>
                            <div class="form-group">
                                <label for="teacherSelect">Select Teacher:</label>
                                <select class="form-control" id="teacherSelect" required>
                                    <option value="Teacher 1">Teacher 1</option>
                                    <option value="Teacher 2">Teacher 2</option>
                                    <option value="Teacher 3">Teacher 3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="classSelect">Select Class:</label>
                                <select class="form-control" id="classSelect" required>
                                    <option value="Class A">Class A</option>
                                    <option value="Class B">Class B</option>
                                    <option value="Class C">Class C</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="subjectSelect">Select Subject:</label>
                                <select class="form-control" id="subjectSelect" required>
                                    <option value="Subject 1">Subject 1</option>
                                    <option value="Subject 2">Subject 2</option>
                                    <option value="Subject 3">Subject 3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="startDateTime">Start Date and Time:</label>
                                <input type="datetime-local" class="form-control" id="startDateTime" required>
                            </div>
                            <div class="form-group">
                                <label for="endDateTime">End Date and Time:</label>
                                <input type="datetime-local" class="form-control" id="endDateTime" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="saveEventBtn">Save Event</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let formulario = document.querySelector("#eventForm");

                var calendar = $('#calendar').fullCalendar({
                    locale: 'ru',
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay,listWeek'
                    },
                    events: "{{ route('full-calender.index') }}",
                    selectable: true,
                    selectHelper: true,
                    select: function (start, end, allDay) {
                        formulario.reset();
                        $('#eventModal').modal('show');
                    },
                    editable: true,
                    initialView: 'dayGridMonth',
                    eventDidMount: function(info) {
                        console.log('info', info);
                        var tooltip = new Tooltip(info.el, {
                            //title: info.event.extendedProps.description,
                            title: 'info.event.extendedProps.description',
                            placement: 'top',
                            trigger: 'hover',
                            container: 'body'
                        });
                    },
                    eventRender: function (event, element) {
                        // Customize how events are rendered
                        // You can access additional fields like event.classe, event.teacher, event.subject, etc.
                        element.find('.fc-title').append('<br/>' + event.classe);
                        element.find('.fc-title').append('<br/>' + event.teacher);
                        element.find('.fc-title').append('<br/>' + event.subject);
                        element.css('background-color', event.color); // Set background color
                    },
                    eventResize: function (event, delta) {

                        console.log('eventResize');
                        var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
                        var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
                        var title = event.title;
                        var id = event.id;

                        // Récupérer les valeurs des autres champs
                        var teacher = event.teacher;
                        var classe = event.classe;
                        var subject = event.subject;
                        var color = event.color;

                        $.ajax({
                            url: "{{ route('full-calender.action') }}",
                            type: "POST",
                            data: {
                                title: title,
                                start: start,
                                end: end,
                                id: id,
                                teacher: teacher,
                                classe: classe,
                                subject: subject,
                                color: color,
                                type: 'update'
                                // Ajouter d'autres champs ici...
                            },
                            success: function (response) {
                                calendar.fullCalendar('refetchEvents');
                                alert("Event Updated Successfully");
                            }
                        });
                    },

                    eventDrop: function (event, delta) {
                        console.log('eventDrop');
                        var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm:ss');
                        var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm:ss');
                        var title = event.title;
                        var id = event.id;

                        // Récupérer les valeurs des autres champs
                        var teacher = event.teacher;
                        var classe = event.classe;
                        var subject = event.subject;
                        var color = event.color;

                        $.ajax({
                            url: "{{ route('full-calender.action') }}",
                            type: "POST",
                            data: {
                                title: title,
                                start: start,
                                end: end,
                                id: id,
                                teacher: teacher,
                                classe: classe,
                                subject: subject,
                                color: color,
                                type: 'update'
                                // Ajouter d'autres champs ici...
                            },
                            success: function (response) {
                                calendar.fullCalendar('refetchEvents');
                                alert("Event Updated Successfully");
                            }
                        });
                    },
                    /*
                    eventClick: function(info) {
                        var evento = info.event;
                        console.log(evento);

                        axios.post(baseURL+"/event/editar/"+info.event.id)
                        .then(
                                (respuesta) => {
                                    formulario.id.value = respuesta.data.id;
                                    formulario.title.value = respuesta.data.title;
                                    formulario.description.value = respuesta.data.description;
                                    formulario.start.value = respuesta.data.start;
                                    formulario.end.value = respuesta.data.end;
                                    $("#event").modal('show');
                                }
                                ).catch(
                                    error => {
                                        if(error.response) {
                                            console.log(error.response.data);
                                        }
                                    }
                                )
                    }
                    */
                    eventClick: function (event) {
                        console.log('eventClick', event);
                        var id = event.id;
                        console.log('id', id);
                        //$('#eventModal').modal('show');

                        $.ajax({
                                url: "{{ route('full-calender.action') }}",
                                type: "POST",
                                data: {
                                    id: id,
                                    type: "list"
                                },
                                success: function (response) {
                                    console.log('eventClick.response', response);
                                    //console.log('eventClick.response.data', response.data);
                                    //calendar.fullCalendar('refetchEvents');
                                    //$('#eventModal').modal('show');
                                    
                                    formulario.reset();

                                    //formulario.id.value = response.data.id;
                                    formulario.eventTitle.value = response.title;
                                    formulario.teacherSelect.value = response.teacher;
                                    formulario.startDateTime.value = response.start;
                                    formulario.endDateTime.value = response.end;
                                    $('#eventModal').modal('show');

                                }
                            });

                        /*
                        if (confirm("Are you sure you want to remove it?")) {
                            var id = event.id;

                            // Récupérer les valeurs des autres champs

                            $.ajax({
                                url: "{{ route('full-calender.action') }}",
                                type: "POST",
                                data: {
                                    id: id,

                                    type: "delete"
                                    // Ajouter d'autres champs ici...
                                },
                                success: function (response) {
                                    calendar.fullCalendar('refetchEvents');
                                    alert("Event Deleted Successfully");
                                }
                            });
                        }
                        */
                    }
                });

                // Handle the Save Event button click
                $('#saveEventBtn').on('click', function () {
                    console.log('saveEventBtn');
                    var title = $('#eventTitle').val();
                    var classe = $('#classSelect').val();
                    var teacher = $('#teacherSelect').val();
                    var subject = $('#subjectSelect').val();
                    var start = $('#startDateTime').val();
                    var end = $('#endDateTime').val();
                    function getRandomColor() {
                        var letters = '0123456789ABCDEF';
                        var color = '#';
                        for (var i = 0; i < 6; i++) {
                            color += letters[Math.floor(Math.random() * 16)];
                        }
                        return color;
                    }
                    var color = getRandomColor();

                    $.ajax({
                        url: "{{ route('full-calender.action') }}",
                        type: "POST",
                        data: {
                            title: title,
                            start: start,
                            end: end,
                            teacher: teacher,
                            classe: classe,
                            subject: subject,
                            color: color,
                            type: 'add'
                        },
                        success: function (data) {
                            $('#eventModal').modal('hide');
                            calendar.fullCalendar('refetchEvents');
                            alert("Event Created Successfully");
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            alert("Error saving event. Please try again.");
                        }
                    });
                });


            });

        </script>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>