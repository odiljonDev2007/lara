@extends('layouts.app')
@section('page-title', __('Delivery'))
@section('page-heading', __('Delivery'))
@section('breadcrumbs')
<li class="breadcrumb-item active">
	@lang('Delivery')
</li>
@stop
@section('content')
@include('partials.messages')
<div class="">
	<div id="calendar"></div>
</div>
<!-- Modal -->
<div class="modal" id="eventModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="eventModalLabel">Событие</h5>
				<div class="close">
					<button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					<span id="cloneEventBtn" title="Создать копию"><i class="fa fa-clone" aria-hidden="true"></i></span>
				</div>
			</div>
			<div class="modal-body">
				<form id="eventForm">
					<div class="form-row">
						<div class="form-group col-sm-4 col-xs-12">
							<label for="eventTitle">Наименование: <font color='red'>*</font></label>
							<input type="text" class="form-control" id="eventTitle" required>
						</div>
						<div class="form-group col-sm-2 col-xs-8">
							<label class="control-label">Источник:</label>
							<select id="sourceClient" class="form-control">
								<option value="0"></option>
							</select>
						</div>
						<div class="form-group col-sm-3 col-xs-6">
							<label class="control-label">Менеджер:</label>
							<select id="id_manager" class="form-control">
								<option value="0"></option>
							</select>
						</div>
						<div class="form-group col-sm-3 col-xs-6">
							<label class="control-label">Статус:</label>
							<select id="statusID" class="form-control">
								<option value=""></option>
							</select>
						</div>
						<div class="form-group" style="display: none;">
							<input type="text" class="form-control" id="id" required>
							<input type="text" class="form-control" id="type" required>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-5">
							<label class="control-label">Имя:</label>
							<input class="form-control" placeholder="Имя пользователя" id="nameContacts" spellcheck=false autocorrect="off" autocapitalize="off" autocomplete="new-nameContacts">
						</div>
						<div class="form-group col-md-5">
							<label class="control-label">Телефон:</label>
							<input class="form-control maskPhone" autocomplete="off" placeholder="(xxx)xxx-xx-xx" id="phoneContacts">
						</div>
						<div class="form-group col-md-2">
							<label class="control-label">Второй:</label>
							<input class="form-control" id="nomer2" type="checkbox">
						</div>
					</div>
					<div class="form-row" id="nomer2s" style="display: none">
						<div class="form-group col-md-5">
							<label class="control-label">Имя:</label>
							<input class="form-control" placeholder="Имя пользователя" id="nameContacts2" spellcheck=false autocorrect="off" autocapitalize="off" autocomplete="new-nameContacts2">
						</div>
						<div class="form-group col-md-5">
							<label class="control-label">Телефон:</label>
							<input class="form-control maskPhone" autocomplete="off" placeholder="(xxx)xxx-xx-xx" id="phoneContacts2">
						</div>
					</div>
					<div class="form-row" id="locationAddress0">
						<div class="form-group col-md-8">
							<label class="control-label">Адрес загрузки: <font color='red'>*</font></label>
							<input class="form-control loadingAddress" autocomplete="off" rows="1" placeholder="Адрес загрузки" name="loadingAddress[0]" id="loadingAddress">
						</div>
						<div class="form-group col-sm-1 col-xs-3" id="warehouses">
							<label class="control-label">Склад:</label>
							<span type="button" class="warehouse btn btn-primary form-control fa fa-map-marker" id="warehouse(0)" title="Заполнить адрес склада" onclick="warehouse(0)"></span>
						</div>
						<div class="form-group col-sm-2 col-xs-6">
							<label class="control-label">Доп. погрузка:</label>
							<button type="button" class="btn btn-primary form-control addAddress" id="addAddress" title="Добавить адрес">
							<i class="fa fa-clone" aria-hidden="true"></i>
							</button>
						</div>
					</div>
					<div class="form-row" id="addresLocation">
						<div class="form-group col-md-8">
							<label class="control-label">Адрес выгрузки: <font color='red'>*</font></label>
							<input class="form-control location" autocomplete="off" rows="1" placeholder="Адрес выгрузки" id="location">
						</div>
						<div class="form-group col-sm-1 col-xs-3" id="calculations">
							<label class="control-label">Расчет:</label>
							<button type="button" class="calculation btn btn-primary form-control" id="calculation" title="Рассчитать км и стоимость">
							<i class="fa fa-calculator" aria-hidden="true"></i>
							</button>
						</div>
						<div class="form-group col-sm-2 col-xs-3">
							<label for="amount">Стоммость:</label>
							<input type="text" class="form-control" disabled="disabled" id="amount" required>
						</div>
						<div class="form-group col-md-1">
							<label class="control-label">Км:</label>
							<input class="form-control" title="Килламетраж от адреса загрузки" disabled="disabled" placeholder="км" id="distance" type="text">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label class="control-label">Подробнее:</label>
							<textarea class="form-control comment" rows="4" style="" id="comment" placeholder="Комментарий"></textarea>
						</div>
						<div class="form-group col-md-6">
							<label class="control-label">Пометка для менеджеров:</label>
							<textarea class="form-control comment" rows="4" style="" id="commentManager" placeholder="Пометка для менеджеров"></textarea>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
							<label for="startDateTime">Начало:</label>
							<input type="datetime-local" class="form-control" id="startDateTime" required onchange="checkDate()">
						</div>
						<div class="form-group col-md-6">
							<label for="endDateTime">Окончание</label>
							<input type="datetime-local" class="form-control" id="endDateTime" required onchange="checkDate()">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Срочно:</label>
							<input type="checkbox" class="form-control" id="urgent" required>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Безнал:</label>
							<input type="checkbox" class="form-control" id="BN" required>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Наемник:</label>
							<input type="checkbox" class="form-control" id="mercenary" required>
						</div>
						<div class="form-group col-sm-2 col-xs-3">
							<label for="tone">Вес (кг): <font color='red'>*</font></label>
							<input type="text" class="form-control" id="tone" required>
						</div>
						<div class="form-group col-sm-2 col-xs-3">
							<label for="price">Сумма:</label>
							<input type="text" class="form-control" id="price" required>
						</div>
						<div class="form-group col-sm-1 col-xs-3" id="copyTexts">
							<label class="control-label">Заявка:</label>
							<button type="button" class="copyText btn btn-primary form-control" id="copyText" data-toggle="modal" title="Текст для водителя">
							<i class="fa fa-clone" aria-hidden="true"></i>
							</button>
						</div>
						<div class="form-group col-sm-4 col-xs-6">
							<label class="control-label">Водитель: <font color='red'>*</font></label>
							<select id="id_driver" class="form-control">
								<option value="0"></option>
							</select>
						</div>
					</div>
					<div class="form-row commentDeletes">
						<div class="form-group col-sm-12 col-xs-12">
							<label class="control-label">Причина удаления:</label>
							<textarea class="form-control comment" style="background: red;color: #fff;" rows="2" style="" id="commentDelete" placeholder="Причина удаления"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger mr-auto" id="deleteEventBtn">Удалить</button>
				<button type="button" class="btn btn-info history pull-right" id="historyEventBtn">История изменений</button>
				<button type="button" class="btn btn-primary" id="saveEventBtn">Сохранить данные</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeEventBtn">Закрыть</button>
			</div>
		</div>
	</div>
</div>
<!-- History -->
<div class="modal" id="historyModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="historyModalLabel">История</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="historyForm">
					<div id="history_output"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
<!-- Search -->
<div class="modal" id="searchModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="searchModalLabel">Поиск</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="searchForm">
					<div id="search_output">
						<input class="form-control" placeholder="Поиск по сделкам..." id="search" spellcheck=false autocorrect="off" autocapitalize="off" autocomplete="new-search">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
<!--<link href='https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.css' rel='stylesheet'
	<link href='https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.css' rel='stylesheet'>>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<!---->
<script src='https://cdn.jsdelivr.net/npm/rrule@2.6.4/dist/es5/rrule.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.13/locales-all.global.min.js'></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
<link rel="stylesheet" href="/delivery/public/assets/css/autoComplete.css">
<script src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js"></script>
<script></script>
@stop
@section('scripts')
{!! HTML::script('assets/js/jquery.inputmask.min.js') !!}
{!! HTML::script('assets/js/as/location.js') !!}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

var config = {
        routes: {
            select: "{{ route('calendarDelivery.select') }}",
			action: "{{ route('calendarDelivery.action') }}",
			All: "{{ route('calendarDelivery.All') }}",
        }
    };

	var count = 1;
	
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	
	var calendar;
	
	let formulario = document.querySelector("#eventForm");

	document.addEventListener('DOMContentLoaded', function() {
	        
	    getMasks();
	
	    var calendarEl = document.getElementById('calendar');
	
	    calendar = new FullCalendar.Calendar(calendarEl, {
	        themeSystem: 'bootstrap',
			datesSet: function(arg) {
				console.log('datesSet()');
				AddManagerFullCalendar();
				
			},
	        customButtons: {
	            myCustomButton: {
	                //icon: "fa fa-search",
	                text: 'Поиск...',
	                click: function() {
	                    //alert('Здесь скоро будет поиск по карточкам!');
	                    $('#searchModal').modal('show');

                        

	                }
	            }
	        },
	        headerToolbar: {
	            left: 'prev,next today myCustomButton',
	            center: 'title',
	            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth,dayGridFourWeek'
	        },
	        views: {
	            dayGridFourWeek: {
					buttonText: '2 недели',
	                type: 'dayGrid',
	                duration: { weeks: 2 }
	            },
	            dayGridMonth: {
					buttonText: '4 недели',
	                type: 'dayGrid',
	                duration: { weeks: 4 }
	            }
	        },
	        locale: 'ru',
	        navLinks: true,
	        //buttonIcons: false,
	        //weekNumbers: true,
	        //navLinks: true,
	        editable: true,
	        selectable: true,
	        slotMinTime: "08:00",
	        slotMaxTime: "20:00",
	        initialView: 'dayGridMonth',
			slotEventOverlap: false,
	        //selectHelper: true,
	        //dayMaxEvents: true,
	        events: {
	            url: config.routes.All,
	            method: 'get',
	            dataType: 'json',
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
	            //extraParams: {
	            //    custom_param1: 'something',
	            //    custom_param2: 'somethingelse'
	            //},
				extraParams: function() {
					console.log('extraParams', calendar);
					return {
						//"Datavisitt": $("#Datavisitt").val()
						"managerSelect": $("#managerSelect").val(),
					}
				},
	            failure: function() {
	                console.log('Ошибка при выполнении запроса!');
	            },
	            color: 'yellow',
	            textColor: 'black'
	        },
	        select: function (info, end, allDay) {
	            //console.log('select');
	            //console.log('start', info.start);
	            //console.log('id_user', {{ $id_user }});
	
	            formulario.reset();
	
	            elementsDisabled(false);
	
	            removeClass();
	
	            elems = document.querySelectorAll('#addressAdd');
	            //console.log('elems', elems);
	            for(i=0; i<elems.length; i++) {
	                elems[i].parentNode.removeChild(elems[i]);
	            }
	            count = 1;
	
	            $('#statusID').val(3).trigger('change'); //ставим при создании статус созвониться
	
	            
	            if ($('input#nomer2').is(':checked')) {
	                $('#nomer2s').show();
	            } else {
	                $('#nomer2s').hide();
	            }
	
	            $('.commentDeletes').hide();
	            
	            
	            formulario.startDateTime.value = moment(info.start).add(8, 'hours').format('YYYY-MM-DD HH:mm:ss');
	            formulario.endDateTime.value = moment(info.start).add(9, 'hours').format('YYYY-MM-DD HH:mm:ss');
	
	            formulario.id_manager.value = {{ $id_user }};
	
	            $("#eventModalLabel").html('Добавление новой доставки');
	
	            $('#type').val('add');
	            $('#eventModal').modal('show');
	
	        },
	        eventDidMount: function (info) {
	
	            console.log('eventDidMount info', info);
	            //console.log('info.el', info.el);
	            //console.log('info.event.start', info.event.start);
	            //console.log('info.event', info.event);
	            //console.log('info.isEnd', info.isEnd);
	            //console.log('info.event.extendedProps', info.event.extendedProps);
	
	            if (calendar.currentData.currentViewType == 'dayGridMonth' || calendar.currentData.currentViewType == 'dayGridFourWeek' || calendar.currentData.currentViewType == 'timeGridWeek') {
	
	                innerHTML = "";
	
	                /*
	                if (info.event.extendedProps.deliveryID) {
	                    innerHTML = innerHTML + info.event.extendedProps.deliveryID + ' | ';
	                }
	                */
	                if (info.event.extendedProps.abbManager) {
	                    innerHTML = innerHTML + info.event.extendedProps.abbManager + ' | ';
	                }
	
	                /*
	                if (info.event.extendedProps.abbDrive) {
	                    innerHTML = innerHTML + info.event.extendedProps.abbDrive + ' | ';
	                }
	                */
	
	                var eventElement = info.el;
	                let delivery = document.createElement('div');
	                delivery.innerHTML = innerHTML;
	                eventElement.prepend(delivery);
	
	                if (info.event.extendedProps.status == '2') {
	                    info.el.closest('div').style.background = 'red';
	                    info.el.closest('a').style.color = '#fff';
	                }
	
	                if (info.event.extendedProps.mercenary) {
	                    info.el.closest('div').style.background = '#c4e1ff';
	                }
	
	                if (info.event.extendedProps.statusID == 6) {
	                    info.el.closest('a').style.color = info.event.backgroundColor
	                    //    color: #179970; для a
	                } else if (info.event.extendedProps.statusID == 22) {
	                    info.el.closest('a').style.color = info.event.backgroundColor;
	                } else if (info.event.extendedProps.statusID == 4) {
	                    info.el.closest('a').style.color = info.event.backgroundColor;
	                }
	
	                if (info.event.extendedProps.BN) {
	                    var eventElement = info.el;
	                    let delivery = document.createElement('div');
	                    delivery.innerHTML = '<i class="fa fa-cc-visa" style="padding-right: 3px;"></i>';
	                    eventElement.prepend(delivery);
	
	                }
	
	                if (info.event.extendedProps.urgent == "1") {
	                    var eventElement = info.el;
	                    let delivery = document.createElement('div');
	                    delivery.innerHTML = '<i class="" style="padding-right: 3px;"> &#128293; </i>';
	                    eventElement.prepend(delivery);
	                }
	
	            } else {
	
	                innerHTML = "";
	
	                if (info.event.extendedProps.deliveryID) {
	                    innerHTML = innerHTML + info.event.extendedProps.deliveryID + ' | ';
	                }
	                if (info.event.extendedProps.abbManager) {
	                    innerHTML = innerHTML + info.event.extendedProps.abbManager + ' | ';
	                }
	
	                //var eventElement = info.el.getElementsByClassName('fc-event-main-frame');
	                var eventElement = info.el.getElementsByTagName('td')[2];
	                //console.log('eventElement', eventElement);
	                let delivery = document.createElement('td');
	                delivery.innerHTML = innerHTML;
	                //eventElement.prepend(innerHTML);
	
	            }
	            
	
	
	            content = "";
	            /*
	            if (info.event.extendedProps.loadingAddress) {
	                content = content + '<u>Адрес загрузки:</u> ' + info.event.extendedProps.loadingAddress + ';</br>';
	            }
	            if (info.event.extendedProps.location) {
	                content = content + '<u>Адрес выгрузки:</u> ' + info.event.extendedProps.location + ';</br>';
	            }
	            */
	
	            content = content + 'С: <u>' + moment(info.event.start).format('HH:mm') + '</u> до: <u>' + moment(info.event.end).format('HH:mm') + '</u>;</br>';
	
	            if (info.event.extendedProps.comment) {
	                content = content + '<u>Комментарий:</u> ' + info.event.extendedProps.comment + ';</br>';
	            }
	            if (info.event.extendedProps.commentManager) {
	                content = content + '<u>Комментарий менеджера:</u> ' + info.event.extendedProps.commentManager + ';</br>';
	            }
	           
	            if (info.event.extendedProps.managerName) {
	                content = content + '<u>Менеджер:</u> ' + info.event.extendedProps.managerName + ';</br>';
	            }
	            if (info.event.extendedProps.driverName) {
	                content = content + '<u>Водитель:</u> ' + info.event.extendedProps.driverName + ';</br>';
	            }
	
	            var title = info.event.extendedProps.deliveryID  + '. ' + info.event.title;
	
	            $(info.el).popover({
	                title: title,
	                placement: 'top',
	                trigger: 'hover',
	                content: content,
	                container: 'body',
	                html: true
	            });
	
	            // если при наведении сделать титл у ссылки
	            //info.el.title = title;
	
	        },
	
	        eventDrop: function (info, delta) {
	                //console.log('eventDrop');
	                //console.log('event', info.event);
	
	                var admin = {{ $admin }};
	                //console.log('admin', admin);
	
	                if (info.event.extendedProps.user_original != {{ $id_user }} & !admin) {
	                    alert("Нельзя изменять сделку другого пользователя!!!");
	                    calendar.refetchEvents();
	                    preventDefault();
	
	                }
	
	                var start  = moment(info.event.start).format('YYYY-MM-DD HH:mm:ss');
	                var end = moment(info.event.end).format('YYYY-MM-DD HH:mm:ss');
	
	                //console.log('start', start);
	                //console.log('end', end);
	
	                var id = info.event.id;
	
	                $.ajax({
	                    url: config.routes.action,
	                    type: "POST",
	                    data: {
	                        start: start,
	                        end: end,
	                        id: id,
	                        type: 'eventDrop'
	                    },
	                    success: function (response) {
	                        alert("Событие успешно перемещено");
	                        calendar.refetchEvents();
	                        $(".popover").remove();
	                    }
	                });
	            },
	            eventClick: function (info, modal) {

					console.log('info', info);
					console.log('modal', modal);

	                
					if (modal == 'true') {
						var id = info;
					} else {
						var id = info.event.id;
					}
	
	                //console.log('eventClick', evento);
	                //var id = evento.id;
	                //console.log('id', id);
	                //$('#eventModal').modal('show');
	
	                $('#statusID').val('').trigger('change');
	
	                $.ajax({
	                        url: config.routes.action,
	                        type: "POST",
	                        data: {
	                            id: id,
	                            type: "list"
	                        },
	                        success: function (response) {
	                            //console.log('eventClick.response', response);
	                            
	                            elementsDisabled(false);
	
	                            elems = document.querySelectorAll('#addressAdd');
	                            //console.log('elems', elems);
	                            for(i=0; i<elems.length; i++) {
	                                elems[i].parentNode.removeChild(elems[i]);
	                            }
	                            count = 1;
	
	                            
	                            formulario.reset();
	                            //console.log('eventClick.formulario', formulario);
	                            
	
	                            $('#statusID').val([response.statusID]).trigger('change');
	
	                            formulario.id.value = response.id;
	                            formulario.eventTitle.value = response.title;
	                            formulario.sourceClient.value = response.sourceClient;
	                            formulario.urgent.checked = response.urgent;
	                            formulario.BN.checked = response.BN;
	                            formulario.mercenary.checked = response.mercenary;
	                            formulario.nomer2.checked = response.nomer2;
	                            formulario.id_manager.value = response.id_manager;
	                            formulario.id_driver.value = response.id_driver;
	
	                            formulario.startDateTime.value = response.start;
	                            formulario.endDateTime.value = response.end;
	                            formulario.tone.value = response.tone;
	                            formulario.price.value = response.price;
	                            formulario.amount.value = response.amount;
	                            formulario.nameContacts.value = response.nameContacts;
	                            formulario.phoneContacts.value = response.phoneContacts;
	                            formulario.nameContacts2.value = response.nameContacts2;
	                            formulario.phoneContacts2.value = response.phoneContacts2;
	
	                            //console.log(JSON.parse(response.loadingAddress));
	                            loadingAddress = JSON.parse(response.loadingAddress);
	                            //console.log(loadingAddress);
	
	                            Object.entries(loadingAddress).forEach(([key, value]) => {
	                                console.log(key, value);
	                                if (key == 0) {
	                                    formulario.loadingAddress.value = value;
	                                } else {
	                                    var html = '';
	                                    html += '<div id="addressAdd">';
	                                    html += '<div class="form-row" id="locationAddress'+key+'">';
	                                    html += '<div class="form-group col-md-8">';
	                                    html += '<label class="control-label">Адрес загрузки: <font color="red">*</font></label>';
	                                    html += '<input class="form-control Address loadingAddress'+key+'" autocomplete="off" rows="1" placeholder="Адрес загрузки" name="loadingAddress['+key+']" id="loadingAddress'+key+'" value="'+value+'">';
	                                    html += '</div>';
	                                    html += '<div class="form-group col-sm-1 col-xs-3" id="warehouses">';
	                                    html += '<label class="control-label">Склад:</label>';
	                                    html += '<span type="button" class="btn btn-primary form-control fa fa-map-marker" id="warehouse('+key+')" title="Заполнить адрес склада" onclick="warehouse('+key+')"></span>';
	                                    html += '</div>';
	                                    html += '<div class="form-group col-sm-2 col-xs-6">';
	                                    html += '<label class="control-label">Статус:</label>';
	                                    html += '<span type="button" class="btn btn-danger form-control fa fa-remove" onclick="removeAddress('+key+')"></span>';
	                                    html += '</div>';
	                                    html += '</div>';
	                                    html += '</div>';
	
	                                    //вставить потом ссылку на адрес выгрузки #location
	                                    $("#addresLocation").before(html);
	                                    ymapsInit("loadingAddress"+count);
	
	                                    count++;
	
	                                }
	                            });
	                            //formulario.loadingAddress.value = response.loadingAddress;
	
	                            formulario.location.value = response.location;
	                            formulario.comment.value = response.comment;
	                            formulario.commentManager.value = response.commentManager;
	                            //formulario.km.value = response.km;
	                            formulario.distance.value = response.distance;
	                            
	
	                            if ($('input#nomer2').is(':checked')) {
	                                $('#nomer2s').show();
	                            } else {
	                                $('#nomer2s').hide();
	                            }
	                            //console.log('response.status', response.status);
	
	                            if (response.status == '2') {
	                                $('.commentDeletes').show();
	                                formulario.commentDelete.value = response.commentDelete;
	                            } else {
	                                $('.commentDeletes').hide();
	                            }
	
	                            $("#eventModalLabel").html(response.id + '. Дата обновления: ' + moment(response.updated_at).format('DD.MM.YYYY HH:mm:ss') + ' ' + response.user);
	
	                            $('#type').val('update');
	
	                            //console.log('$id_user', {{ $id_user }});
	                            //console.log('response.user_original', response.user_original);
	                            var admin = {{ $admin }};
	                            console.log('response.status', response.status);
	
	                            if ({{ $id_user }} != 2) {
	                                //document.getElementById("addAddress").disabled = true;
	                            }
	                            if (moment(response.end).format('YYYY-MM-DD') < moment().format('YYYY-MM-DD')) {
	                                console.log('меньше');
	                            } else {
	                                console.log('больше');
	                            }
	
	                            if (response.user_original != {{ $id_user }} & !admin || moment(response.end).format('YYYY-MM-DD') < moment().format('YYYY-MM-DD') & !admin || response.status == '2' & !admin) {
	
	
	                                console.log('не равно');
	
	                                elementsDisabled(true);
	
	                                $("#closeEventBtn").attr('disabled', false);
	                                $("#close").attr('disabled', false);
	
	                            }
	                            if (response.user_original == {{ $id_user }} & !admin) {
	                                $("#statusID").prop('disabled', false);
	                                $("#saveEventBtn").prop('disabled', false);
	                            }
	
	                            
	
	                            $('#eventModal').modal('show');
	
	                        }
	                    });
	
	            }
	    
	
	
	
	    });
	
	

	
	    calendar.render();
	
	
	
	});
	



		

	
	
	
	
	
	
	

	    
	

	
	
	


	
	    
	
	








		// Раздел. Доставки



		// Раздел. Доставки. Добавление нового адреса промежуточной точки.
	    $('#addAddress').on('click', function () {
	        console.log('addAddress');
	
	        //var telnum = parseInt($("#form_add_details_uuidtmpl").find("div.locationAddress:last").attr("id").slice(3))+1;
	        //console.log('telnum',telnum);
	
	        var html = '';
	        html += '<div id="addressAdd">';
	        html += '<div class="form-row" id="locationAddress'+count+'">';
	        html += '<div class="form-group col-md-8">';
	        html += '<label class="control-label">Адрес загрузки: <font color="red">*</font></label>';
	        html += '<input class="form-control Address loadingAddress'+count+'" autocomplete="off" rows="1" placeholder="Адрес загрузки" name="loadingAddress['+count+']" id="loadingAddress'+count+'">';
	        html += '</div>';
	        html += '<div class="form-group col-sm-1 col-xs-3" id="warehouses">';
	        html += '<label class="control-label">Склад:</label>';
	        html += '<span type="button" class="btn btn-primary form-control fa fa-map-marker" id="warehouse('+count+')" title="Заполнить адрес склада" onclick="warehouse('+count+')"></span>';
	        html += '</div>';
	        html += '<div class="form-group col-sm-2 col-xs-6">';
	        html += '<label class="control-label">Статус:</label>';
	        html += '<span type="button" class="btn btn-danger form-control fa fa-remove" onclick="removeAddress('+count+')"></span>';
	        html += '</div>';
	        html += '</div>';
	        html += '</div>';
	
	        //onclick="deleteField('+telnum+');"
	
	        count2 = count - 1;
	        console.log('count', count);
	
	
	        console.log('count', count2);
	        //вставить потом ссылку на адрес выгрузки #location
	        $("#addresLocation").before(html);
	
	        ymapsInit("loadingAddress"+count);
	        
	
	        count++;
	
	    });


		// Раздел. Доставки. Дата окончания не может быть меньше даты начала, устанавливаю равную дату.
	    function checkDate() {
	        console.log('checkDate');
	        var start = $('#startDateTime').val();
	        var end = $('#endDateTime').val();
	        let formulario = document.querySelector("#eventForm");
	
	        if (end <= start) {
	            formulario.endDateTime.value = moment(start).add(2, 'hours').format('YYYY-MM-DD HH:mm:ss');
	            alert("Дата окончания не может быть меньше даты начала, устанавливаю равную дату!");
	            return false;
	        }
	            return true;
	        
	    }
	
		// Раздел. Доставки. Проверка занять ли собственный транспорт на текущий промежуток времени.
	    $('#id_driver').on('change', function() {
	        checkDateFails();
	    });

		// Раздел. Доставки. Проверка занять ли собственный транспорт на текущий промежуток времени.
	    function checkDateFails(e) {
	        
	        var id = $('#id').val();
	        var start = moment($('#startDateTime').val()).format('YYYY-MM-DD HH:mm:ss');
	        var end = moment($('#endDateTime').val()).format('YYYY-MM-DD HH:mm:ss');
	        var id_driver = $('#id_driver').val();
	
	        //console.log('checkDate start', start);
	
	        //сначала отбор даты, далее какая машина проверяем, далее выводим еще что за менеджер и ид сделки
	        return $.ajax({
	            url: config.routes.select,
	            type: "get",
	            async: false,
	            data: {
	                id: id,
	                start: start,
	                end: end,
	                id_driver: id_driver,
	                type: 'checkDate'
	            },
	            success: function (data) {
	
	                console.log('checkDate data', data.length);
	                if (data.length) {
	
	                    Object.entries(data).forEach(([key, value]) => {
	                        //console.log(key, value);
	                        const messageText = "На данное время машина занята! </br> Номер карты: " + value['id'] + "</br> Начало доставки: " + moment(value['start']).format('HH:mm') + "</br> Окончание доставки: " + moment(value['end']).format('HH:mm') + "</br> Менеджер: " + value['managerName'];
	                        const message = document.createElement('div');
	                        message.className = 'alert alert-warning alert-dismissible fade show';
	                        message.innerHTML = `${messageText}<button type="button" class="close" data-dismiss="alert">×</button>`;
	                        messages.appendChild(message);
	
	                    });
	                    return false;
	
	                } else {
	                    return true;
	                }
	
	            },
	            error: function (xhr, status, error) {
	                console.error(xhr.responseText);
	                alert("Ошибка выбора.");
	            }
	
	        }).responseJSON;
	
	        //console.log('ajax', ajax);
	
	        //return true;
	    }

		// Раздел. Доставки. Фильтр по менеджерам.
		function AddManagerFullCalendar() {
			if($("select[id=managerSelect]").length < 1) {
				var selectHTML =
					"<select id=\"managerSelect\" class=\"fc-myCustomButton-button fc-button fc-button-primary\">" +
						"<option value=''>Все менеджеры</option>" +
					"</select>";
					
				// depending on where on the bar you want it, use prependTo or appendTo, and alter the array number
				$(selectHTML).appendTo($(".fc-header-toolbar .fc-toolbar-chunk")[0]);
				
				$("#managerSelect").on('change', function() {
					// some onchange function
					console.log('managerSelect');
					calendar.refetchEvents();
				});
			}
		}

		// Раздел. Доставки. Удаление подсвечиваний для заполненных или обязательных полей заполнения.
	    function removeClass() {
	        $("#distance").removeClass('green');
	        $("#amount").removeClass('green');
	        $("#location").removeClass('req');
	        $("#id_driver").removeClass('req');
	        $("#tone").removeClass('req');
	    }

		// Раздел. Доставки. Поля не заполнены или идет их обновление.
	    function addClass() {
	        $("#distance").addClass('req');
	        $("#amount").addClass('req');
	    }


	    // Раздел. Доставки. Функция вставки адреса загрузки по умолчанию.
	    function warehouse (id) {
	        console.log('id', id);
	        console.log('loadingAddress', '#loadingAddress'+id);
	        removeClass();
	        addClass();
	
	        if (id == 0) {
	            $('#loadingAddress').val('Московская область, Осташковское шоссе, вл14Б');
	        } else {
	            $('#loadingAddress'+id).val('Московская область, Осташковское шоссе, вл14Б');
	        }
	        
	    }

			
	    // Раздел. Доставки. Функция удаления дополнительного поля доставки.
	    function removeAddress (id) {
	
	        console.log('id', id);
	        console.log('removeAddress', '#locationAddress'+id);
	        removeClass();
	        addClass();
	
	        $('#locationAddress'+id).remove();
	    }

		// Раздел. Доставки. Поле тонаж только цифры
	    $("#tone").bind("change keyup input click", function() {
	        if (this.value.match(/[^0-9]/g)) {
	            this.value = this.value.replace(/[^0-9]/g, '');
	        }
	    });


	
	    // Раздел. Доставки. Получаем водителей из справочника: Машины. (когда не нужно получать водителей из списка пользователей)
	    $.ajax({
	        url: config.routes.select,
	        type: 'get',
	        data: {
	            type: "id_driver"
	        },
	        success: function (data) {
	            console.log('id_driver', data);
	            
	            data.forEach((item) => {
	                $('#id_driver').append('<option value="' + item.id + '">' + item.name + '</option>');
	            });
	        }
	    });

		// Раздел. Доставки. Обновление списка сделок через 30 секунд. /// --- Реализовать через историю изменений.
	    setInterval(function () {
	        console.log('calendar.refetchEvents()');
	        document.querySelectorAll('.popover').forEach(e => e.remove());
	        calendar.refetchEvents();
	
	    }, 30000);
	
		// Раздел. Доставки. Сохранить изменения в карточке.
	        $('#saveEventBtn').on('click', function () {
	            console.log('saveEventBtn');
	            
	            var price = $('#price').val();             
	
	            if (!price || price == 0) {
	                console.log('Вывод price: ', price);
	
	                const messageText = "Не заполнена сумма";
	                const message = document.createElement('div');
	                message.className = 'alert alert-danger alert-dismissible fade show';
	                message.innerHTML = `${messageText}<button type="button" class="close" data-dismiss="alert">×</button>`;
	                messages.appendChild(message);
	
	                return false;
	            }
	
	            var checkDate = checkDateFails();
	            console.log('checkDate.length', checkDate.length);
	
	            if (checkDate.length) {
	                console.log('Количество элементов length: ', checkDate.length);
	                return false;
	            }
	
	            var values = [];
	            $("[name^='loadingAddress']").each(function() {
	                values.push($(this).val());
	            });
	
	            console.log('values', values);
	
	            result = JSON.stringify(values);
	            console.log(result); // => [1,2,3,4,5]
	
	
	            var id = $('#id').val();
	            var type = $('#type').val();
	            var title = $('#eventTitle').val();
	            var sourceClient = $('#sourceClient').val();
	            var nameContacts = $('#nameContacts').val();
	            var phoneContacts = $('#phoneContacts').val();
	
	            var nameContacts2 = $('#nameContacts2').val();
	            var phoneContacts2 = $('#phoneContacts2').val();
	
	            var urgent = $('#urgent').is(':checked') ? 1 : 0;
	            var BN = $('#BN').is(':checked') ? 1 : 0;
	            var mercenary = $('#mercenary').is(':checked') ? 1 : 0;
	            var nomer2 = $('#nomer2').is(':checked') ? 1 : 0;
	
	            //var loadingAddress = $('#loadingAddress').val();
	            var loadingAddress = JSON.stringify(values);
	            console.log(loadingAddress); 
	
	            var location = $('#location').val();
	            var comment = $('#comment').val();
	            var commentManager = $('#commentManager').val();
	            var distance = $('#distance').val();
	            var statusID = $('#statusID').val();
	            var id_manager = $('#id_manager').val();
	            var id_driver = $('#id_driver').val();
	            var tone = $('#tone').val();
	            var amount = $('#amount').val();
	
	            var start = moment($('#startDateTime').val()).format('YYYY-MM-DD HH:mm:ss');
	            var end = moment($('#endDateTime').val()).format('YYYY-MM-DD HH:mm:ss');
	
	            $.ajax({
	                url: config.routes.action,
	                type: "POST",
	                data: {
	                    id: id,
	                    title: title,
	                    start: start,
	                    end: end,
	                    sourceClient: sourceClient,
	                    urgent: urgent,
	                    BN: BN,
	                    mercenary: mercenary,
	                    nomer2: nomer2,
	                    nameContacts: nameContacts,
	                    phoneContacts: phoneContacts,
	                    nameContacts2: nameContacts2,
	                    phoneContacts2: phoneContacts2,
	                    loadingAddress: loadingAddress,
	                    location: location,
	                    comment: comment,
	                    commentManager: commentManager,
	                    distance: distance,
	                    statusID: statusID,
	                    id_manager: id_manager,
	                    id_driver: id_driver,
	                    tone: tone,
	                    price: price,
	                    amount: amount,
	                    type: type
	                },
	                success: function (data) {
	                    formulario.reset();
	                    $('#eventModal').modal('hide');
	                    calendar.refetchEvents();
	
	                    console.log('success сохранение');
	
	                },
	                error: function (xhr, status, error) {
	                    console.error(xhr.responseText);
	                    alert("Ошибка сохранения. Пожалуйста проверьте поля.");
	                }
	            });
	            
	            /**/
	
	        });
	
			// Раздел. Доставки. Удаление сделки.
	        $('#deleteEventBtn').on('click', function () {
	            console.log('deleteEventBtn');
	
	            var admin = {{ $admin }};
	            var id = $('#id').val();
	            var commentDelete = '';
	            console.log('admin', admin);
	
	            if (confirm("Действительно хотите удалить?")) {
	
	                if (!admin) {
	                    const result = prompt('Пожалуйста, напишите почему удаляете сделку?');
	                    console.log('что в пусто', result);
	                    if (result === null || result == '') {
	                        alert('Вы отказались от ввода');
	                        preventDefault();
	
	                    } else {
	                        console.log('Введенный комментарий', result);
	                        var status = '2';
	                        commentDelete = result;
	                    }
	                } else {
	                    var status = '0';
	                }
	
	
	                $.ajax({
	                    url: config.routes.action,
	                    type: "POST",
	                    data: {
	                        id: id,
	                        status: status,
	                        commentDelete: commentDelete,
	                        type: 'delete'
	                    },
	                    success: function (data) {
	                        formulario.reset();
	                        $(".popover").remove();
	                        $('#eventModal').modal('hide');
	                        calendar.refetchEvents();
	                    },
	                    error: function (xhr, status, error) {
	                        console.error(xhr.responseText);
	                        alert("Ошибка удаления.");
	                    }
	                });
	
	            }
	        });
	
	        /*
	        $('#warehouse').on('click', function () {
	            console.log('warehouse');
	            $('#loadingAddress').val('Московская область, Осташковское шоссе, вл14Б');
	        });
	        */
	        
	
	        $('#calculation').on('click', function () {
	            console.log('calculation');
	
	            var location = $('#location').val();
	            //var loadingAddress = $("#loadingAddress").val();
	
	            var loadingAddress = [];
	            $("[name^='loadingAddress']").each(function() {
	                loadingAddress.push($(this).val());
	            });
	
	            console.log('loadingAddress', loadingAddress);
	
	            var id_driver = $("#id_driver").val();
	            var tone = $("#tone").val();
	
	
	            if (!location || !loadingAddress || !tone || tone == '0' || id_driver == '0') {
	
	                if (!location) {
	                    alert("Заполни адрес выгрузки");
	                    $("#location").addClass('req');
	                }
	
	                if (!loadingAddress) {
	                    alert("Заполни адрес погрузки");
	                }
	
	                if (id_driver == '0') {
	                    alert("Не указан водитель");
	                    $("#id_driver").addClass('req');
	                }
	
	                if (!tone || tone == '0') {
	                    alert("Укажи количество тонн");
	                    $("#tone").addClass('req');
	                }
	                
	            } else {
	                $("#location").removeClass('req');
	                $("#id_driver").removeClass('req');
	                $("#tone").removeClass('req');
	
	                removeClass();
	                addClass();
	            }
	
	            if (id_driver) {
	                var obj2 = $.ajax({
	                    url: config.routes.select,
	                    method: 'get',
	                    data: {
	                        id_driver: id_driver,
	                        type: "id_driver_adress"
	                    },
	                    dataType: 'json',
	                    cache: false,
	                    async: false,
	                }).responseJSON; 
	                console.log('obj2', obj2[0]['address']);
	                var addressHome = obj2[0]['address'];
	            } else {
	                var addressHome = "";
	            }
	
	            var distanceAll = 0;
	
	            if (location && loadingAddress) {
	                if (typeof ymaps  !==  "undefined") {
	                    ymaps.ready(init);
	  
	                    function init() {
	                        console.log("Дистанция");
	
	                        //var home = 'Москва, улица Генерала Кузнецова';
	                        //var addressHome = $('#addressHome').val();
	
	                        if (!addressHome) {
	                            alert("Не указан домашний адрес водителя!")
	                        }
	                        
	                        console.log('loadingAddress.length', loadingAddress.length);
	                        if (loadingAddress.length) {
	                            var points = [addressHome];
	                            Object.entries(loadingAddress).forEach(([key, value]) => {
	                                console.log(key, value);
	                                //start = 'points'+key;
	                                //console.log('start', start);
	                                //start = value;
	                                if (value == '') {
	                                    alert("Есть пустой адрес загрузки, не можем рассчитать!")
	                                    preventDefault();
	
	                                }
	                                points.push(value);
	
	                            });
	                        }
	
	                        points.push(location);
	                        points.push(addressHome);
	
	                        console.log('points', points);
	                        console.log('addressHome', addressHome);
	                        //var start_point = loadingAddress;
	                        
	                        var loading2 = [addressHome, addressHome];
	                        console.log('loading2', loading2);
	
	                        /*
	                        var end_point = location;
	
	                        console.log('loading2', loading2);
	
	                        if (loading2) {
	                            var loading2 = [addressHome, start_point, start_point2, end_point, addressHome];
	
	                            console.log('loading points', points);
	                        } else {
	                            var points = [addressHome, start_point, end_point, addressHome];
	                            console.log('loading points', points);
	                        }
	                            */
	
	                        
	                        //1
	                        
	                        ymaps.route(points, {
	                            mapStateAutoApply: true,
	                            avoidTrafficJams: false, //Позволяет прокладывать мультимаршрут с учетом информации о пробках. При использовании опции учитывайте, что объезд пробок не всегда возможен.
	                            multiRoute: false,
	                            reverseGeocoding: true,
	                            routingMode: "auto", //автомобильный маршрут
	                            viaIndexes: []
	                        }).then(function (route) {
	                      
	                            var distance = Math.round(route.getLength() / 1000);
	                            console.log("Дистанция, до объекта", distance);
	                            distanceAll = distanceAll + distance;
	                            //$('#distance').val(distance);
	                            console.log("distanceAll", distanceAll);
	                            $('#distance').val(distanceAll);
	                            $("#distance").addClass('green');
	
	
	                            //calculators = calculators(tone);
	
	                            var tone = $('#tone').val();
	
	                            $.ajax({
	                                url: "{{ route('calendarDelivery.calculator') }}",
	                                type: "get",
	                                data: {
	                                    tone: tone,
	                                    distanceAll: distanceAll,
	                                    type: 'tone'
	                                },
	                                success: function (data) {
	                                    console.log('data', data);
	                                    $('#amount').val(data);
	                                    $("#amount").addClass('green');
	
	                                },
	                                error: function (xhr, status, error) {
	                                    console.error(xhr.responseText);
	                                    alert("Ошибка загрузки истории.");
	                                }
	                            });
	                              
	                        }, function (error) {
	                            // Ошибка error.message
	                        });
	
	                    }
	
	                }
	            }
	        });





	
</script>



{!! HTML::script('assets/js/as/calendar.js') !!}

@stop
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="assets/css2/font-awesome.css" rel="stylesheet" />
<style>
	.select2-container {
	background-clip: padding-box;
	background-color: #fff;
	border: 1px solid #ced4da;
	border-radius: .25rem;
	color: #495057;
	display: block;
	font-size: .9rem;
	font-weight: 400;
	height: calc(1.6em + 1rem + 2px);
	line-height: 1.6;
	padding: .5rem .75rem;
	transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
	width: 100%;
	}
	.select2-container--default .select2-selection--single {
	background-color: #fff;
	border: 0;
	border-radius: 0;
	}
	.select2-container--default .select2-selection--single .select2-selection__rendered {
	line-height: 22px;
	}
	.form-group {
	margin-bottom: 3px;
	}
	.req {
	border: 2px solid red;
	}
	.green {
	border: 2px solid green;
	}
	@media (max-width: 575px) {
	$('.px-4').css('padding-left', '0px !important');
	$('.px-4').css('padding-right', '0px !important');
	#fc-dom-1 {
	font-size: 12px;
	margin: 5px;
	}
	#calendar .fc-header-toolbar button {
	padding: 0px 3px;
	}
	.fc .fc-button-group {
	display: inline-table;
	}
	}
</style>
@stop