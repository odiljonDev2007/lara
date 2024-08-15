@extends('layouts.app')
@section('page-title', __('Call'))
@section('page-heading', __('Call'))
@section('breadcrumbs')
<li class="breadcrumb-item active">
	@lang('Call')
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
				</div>
			</div>
			<div class="modal-body">
				<form id="eventForm">
					<div class="form-row">
						<div class="form-group col-md-4">
							<label class="control-label">Имя:</label>
							<input class="form-control" placeholder="Имя пользователя" id="nameContacts" spellcheck=false autocorrect="off" autocapitalize="off" autocomplete="new-nameContacts">
						</div>
						<div class="form-group col-md-4">
							<label class="control-label">Телефон:</label>
							<input class="form-control maskPhone" autocomplete="off" placeholder="(xxx)xxx-xx-xx" id="phoneContacts">
						</div>
						<div class="form-group col-md-2">
							<label class="control-label">Источник:</label>
							<select id="sourceClient" class="form-control">
								<option value="0"></option>
							</select>
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

					<div class="form-row">
						<div class="form-group col-md-3">
							<label class="control-label">Разговор:</label>
							<input class="form-control" id="user_talk_time">
						</div>
						<div class="form-group col-md-3">
							<label class="control-label">Дозвон:</label>
							<input class="form-control" id="dozvon">
						</div>
						<div class="form-group col-md-6" id="downloadCall">
						</div>
					</div>


					<div class="form-row" id="addresLocation">
						<div class="form-group col-md-8">
							<label class="control-label">Адрес объекта: <font color='red'>*</font></label>
							<input class="form-control location" autocomplete="off" rows="1" placeholder="Адрес объекта" id="location">
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
							<input type="date" class="form-control" id="startDateTime" required onchange="checkDate()">
						</div>
						<div class="form-group col-md-6">
							<label for="endDateTime">Окончание</label>
							<input type="date" class="form-control" id="endDateTime" required onchange="checkDate()">
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Срочно:</label>
							<input type="checkbox" class="form-control" id="urgent" required>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Комбо:</label>
							<input type="checkbox" class="form-control" id="combine" required>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Замер:</label>
							<input type="checkbox" class="form-control" id="zamer" required>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Сетка:</label>
							<input type="checkbox" class="form-control" id="setka" required>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">П. марка:</label>
							<input type="checkbox" class="form-control" id="increased" required>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Безнал:</label>
							<input type="checkbox" class="form-control" id="BN" required>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Сделка:</label>
							<input type="checkbox" class="form-control" id="deals" disabled>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">СМС:</label>
							<input type="checkbox" class="form-control" id="send_SMS" required>
						</div>
						<div class="form-group col-sm-1 col-xs-3" id="copyTexts">
							<label class="control-label">Заявка:</label>
							<button type="button" class="copyText btn btn-primary form-control" id="copyText" data-toggle="modal" title="Текст для водителя">
							<i class="fa fa-clone" aria-hidden="true"></i>
							</button>
						</div>
					</div>


					<div class="form-row">
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Этаж:</label>
							<input type="text" class="form-control" id="floor" placeholder="Этаж" required>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Под-д:</label>
							<input type="text" class="form-control" id="entrance" placeholder="Подъезд" required>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">м2:</label>
							<input type="text" class="form-control" id="m2" placeholder="Площадь" required>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Слой:</label>
							<input type="text" class="form-control" id="thickness" placeholder="Слой" required>
						</div>
						<div class="form-group col-sm-1 col-xs-2">
							<label class="control-label">Км от Мкада:</label>
							<input type="text" class="form-control" id="km" placeholder="От Мкада" required>
						</div>
						<div class="form-group col-sm-2 col-xs-2">
							<label class="control-label">За м2:</label>
							<input type="text" class="form-control" id="m2_price" placeholder="Цена за м2" required>
						</div>
						<div class="form-group col-sm-2 col-xs-3">
							<label for="price">Сумма (Менеджерам):</label>
							<input type="text" class="form-control" id="price_styazhka" placeholder="Сумма по виду работ" required>
						</div>
						<div class="form-group col-sm-2 col-xs-3">
							<label for="price">Сумма (Прорабам):</label>
							<input type="text" class="form-control" id="price" placeholder="Сумма общая" required>
						</div>
						<div class="form-group col-sm-1 col-xs-3" id="copyTexts">
							<label class="control-label">Калькулятор:</label>
							<button type="button" class="copyText btn btn-primary form-control" id="copysCalculate" data-toggle="modal" title="Текст для водителя">
							<i class="fa fa-calculator" aria-hidden="true"></i>
							</button>
						</div>
					</div>


					<div class="form-row">
						<div class="form-group col-sm-3 col-xs-6">
							<label class="control-label">Тип сделки: <font color='red'>*</font></label>
							<select id="statusObject" class="form-control">
							</select>
						</div>
						<div class="form-group col-sm-3 col-xs-6">
							<label class="control-label">Статус: <font color='red'>*</font></label>
							<select id="statusID" class="form-control">
								<option value=""></option>
							</select>
						</div>
						<div class="form-group" style="display: none;">
							<input type="text" class="form-control" id="id" required>
							<input type="text" class="form-control" id="type" required>
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
				<button type="button" class="btn btn-success mr-auto" id="addPhoneEventBtn">Создать сделку</button>
				<button type="button" class="btn btn-success mr-auto" id="historyPhoneEventBtn">Звонки с клиентом</button>
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
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.js'></script>
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
            select: "{{ route('calendarCall.select') }}",
			action: "{{ route('calendarCall.action') }}",
			All: "{{ route('calendarCall.All') }}",
			refetch: "{{ route('calendarCall.refetch') }}",
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
	            right: 'dayGridMonth,dayGridDay,listDay,listMonth,dayGridFourWeek'
	        },
	        views: {
				listDay: {
					buttonText: 'Список день'
				},
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
	        editable: true,
	        selectable: false,
	        initialView: 'dayGridDay',
			eventMaxStack: "0",
			defaultTimedDay: false,
			//allDaySlot: false, // скрыть весь день, где не указано время в дате
			eventOrderStrict: true,
			eventOrder: "-start",
			displayEventTime : false, //не показывать время в повестке дня

			slotLabelFormat: [
				{ day: 'numeric', month: 'numeric', year: 'numeric' }, // top level of text
				{ hour: 'numeric' } // lower level of text
			],
	        events: {
	            url: config.routes.All,
	            method: 'get',
	            dataType: 'json',
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
				extraParams: function() {
					console.log('extraParams', calendar);
					return {
						//"Datavisitt": $("#Datavisitt").val()
						"callSelect": $("#callSelect").val(),
					}
				},
	            failure: function() {
	                console.log('Ошибка при выполнении запроса!');
	            },
	            color: 'yellow',
	            //textColor: 'black'
	        },
			eventClassNames: function(info) {
				//console.log('info', info);
				//console.log('info.event.extendedProps', info.event.extendedProps);
				if (info.event.extendedProps.callID) {
					return [ 'sourceClient' ]
				} else {
					return [ 'sourceClient' ]
				}
			},
			eventContent: function(info, createElement) {

				var html = [];

				var innerText = '';

				if (info.event.extendedProps.accountcode == 'inbound') {
					innerText = innerText + '<b> (ВХ) </b>';
				} else {
					innerText = innerText + ' (ИСХ) ';
				}
				
				innerText = innerText + info.event.extendedProps.title_time;


				// проработать звонки, на кого какой идет по онлайн пбх
				if (info.event.extendedProps.destination_number == "132" || info.event.extendedProps.destination_number == "10" || info.event.extendedProps.destination_number == "5200")  {
    		        innerText = innerText + " | <b>Валентин | </b>";
    		    } else if (info.event.extendedProps.destination_number == "130" || info.event.extendedProps.destination_number == "5201") {
    		        
    		        if (info.event.extendedProps.start_stamp > "1608722279") {
    		            innerText = innerText + " | <b>Вагонка | </b>";
					}
    		    } else if (info.event.extendedProps.destination_number == "11") {
					innerText = innerText + " | <b>Звонок на ЛидерСтройГрупп | </b>";
				}

				innerText = innerText + " (+7" + info.event.extendedProps.phoneContacts + ")"  + " <u>" + info.event.extendedProps.nameContacts + "</u>";

				// здесь вставить имя вользователя из таблицы контакты
				//innerText = innerText + " (+7" + info.event.extendedProps.phoneContacts + ")";

				var location_zapolnen = "";
    		    if (info.event.extendedProps.location) {
        		    
        		    var location = info.event.extendedProps.location.split(",")[0];
        		    var location_replace = info.event.extendedProps.location.replace(/городской|округ/ig,'');
        		    
        		    if (location.trimStart() == "Московская область") {
        		        
        		        var arr = location_replace.split(",");
                        
                        arr.splice(2);
                        arr.join(",");
                        arr.splice((arr.length  - 2), 1);
                        arr.join(",");

        		    } else {
        		        
        		        var arr = location_replace.split(",");
                        arr.splice(2);
                        arr.join(",");
                        arr.join(",");
        		    }
        		    
    		        location_zapolnen = arr + " | ";
    		    }
    		    
    		    
    		    var param = "";
    		    if (info.event.extendedProps.m2 > 0)  {
    		        param = " | <b> " + info.event.extendedProps.m2 + "м2 | " + location_zapolnen + " </b>" ;
    		    } else {
    		        param = location_zapolnen;
    		    }

				innerText = innerText + param;
				innerText = innerText + ' (Разговор: ' + moment(info.event.extendedProps.user_talk_time*1000).format('mm:ss') + '; Дозвон: ' + (info.event.extendedProps.time_call - info.event.extendedProps.user_talk_time) + ') ';
				

				
				//var changeTitle =  + partner_zapolnen + "<u>" + schedule.name_contacts + "</u>" + zapolnen + leads + drive + send_SMS + otkazStatus;

				//console.log('info.event.extendedProps.m2', info.event.extendedProps.m2);
				var zapolnen = "";
    		    if (info.event.extendedProps.m2 || info.event.extendedProps.thickness || info.event.extendedProps.price || info.event.extendedProps.comment)  {
    		        var zapolnen = " | <b style='background-color: #0025ff;padding: 3px 8px;'>Заполнен</b>";

					//console.log('m2', info.event.extendedProps.m2);
					//console.log('thickness', info.event.extendedProps.thickness);
					//console.log('price', info.event.extendedProps.price);
					//console.log('comment', info.event.extendedProps.comment);
					
    		    }

				innerText = innerText + zapolnen;

				//console.log('info.event.extendedProps.sourceCall', info.event.extendedProps.sourceCall);
				//console.log('info.event.extendedProps.sourceCallColor', info.event.extendedProps.sourceCallColor);
					
				// добавить сравнение источника или же, в БД в источники сразу добавлять цвет
				// ПЕРЕПИСАТЬ

				const screenWidth = window.screen.width;
				const screenHeight = window.screen.height;
				//console.log('screenWidth', screenWidth);

				if (screenWidth > 850 & info.event.extendedProps.sourceCall > 0) {
					var innerText = innerText + '<span style="position: absolute;right:2%;font-weight: bold;">' + info.event.extendedProps.sourceCallName + '</span>';
					
				}




				//return createElement('i', {}, innerText)
				return { html: innerText }; // Render HTML in event title

				//obj.borderColor = "#ef1deb";

			},

	        eventDidMount: function (info) {
	
	            //console.log('eventDidMount info', info);
	            //console.log('info.el', info.el);
	            //console.log('info.event.start', info.event.start);
	            //console.log('info.event', info.event);
	            //console.log('info.isEnd', info.isEnd);
	            //console.log('info.event.extendedProps', info.event.extendedProps);
	
	            if (calendar.currentData.currentViewType == 'dayGridMonth' || calendar.currentData.currentViewType == 'dayGridFourWeek' || calendar.currentData.currentViewType == 'dayGridDay') {
	
	                innerHTML = "";

					//выводим слева черту источника
					info.el.closest('a').style['border-left-color'] = info.event.extendedProps.sourceCallColor;
	                
	

					if (info.event.extendedProps.statusCall) {
	                    info.el.closest('a').style.color = info.event.backgroundColor;
	                }

					if (info.event.extendedProps.uniqueCall == 1) {

						console.log('uniqueCall', info.event.extendedProps.uniqueCall);
						//нужно тянуть из БД color для не отвеченного если статус 12, для 11 другой цвет, 14 статус - уникальный
						//info.el.closest('a').style['background'] = "linear-gradient(to right," +  color.bgColor + ", #40f100)";

						info.el.closest('a').style['background'] = "linear-gradient(to right," +  info.event.backgroundColor + ", #40f100)";
						//obj.bgColor = color.bgColor;
						info.el.closest('a').style['background-color'] = '#ff0000';
					}



					/*
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
				*/

	            } else {
	
					/*
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

					*/
	            }
	            
	
	
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
					$('#statusObject').val('').trigger('change');
	
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
	
	                            elems = document.querySelectorAll('#downloadAdd');
	                            //console.log('elems', elems);
	                            for(i=0; i<elems.length; i++) {
	                                elems[i].parentNode.removeChild(elems[i]);
	                            }
	                            count = 1;
	
	                            
	                            formulario.reset();
	                            //console.log('eventClick.formulario', formulario);
	                            
	
	                            $('#statusID').val([response.statusID]).trigger('change');

								
								if (response.statusObject) {
									//$('#statusObject').val(response.statusObject);
									$("#statusObject option[value=" + response.statusObject + "]").attr('selected', 'true');
								} else {
									console.log('response.statusObject', response.statusObject);
									$("#statusObject option[value='1']").attr('selected', 'true');
									//$('#statusObject').val('1');
								}
	                            
								
	
	                            formulario.id.value = response.id;
	                            formulario.sourceClient.value = response.sourceClient;
	                            formulario.urgent.checked = response.urgent;
	                            formulario.BN.checked = response.BN;
	                            formulario.nomer2.checked = response.nomer2;
	
	                            formulario.startDateTime.value = response.start;
	                            formulario.endDateTime.value = response.end;
	                            formulario.price.value = response.price;
	                            //formulario.amount.value = response.amount;
	                            formulario.nameContacts.value = response.nameContacts;
	                            formulario.phoneContacts.value = response.phoneContacts;
	                            formulario.nameContacts2.value = response.nameContacts2;
	                            formulario.phoneContacts2.value = response.phoneContacts2;

								formulario.user_talk_time.value = moment(response.user_talk_time*1000).format('mm:ss');
								formulario.dozvon.value = response.time_call - response.user_talk_time;
	                            
								console.log('download', response.download);
								console.log('formulario', formulario);

								var html = '';
	                            html += '<div id="downloadAdd">';
	                            html += '<label class="control-label">Запись: </label>';
	                            html += '<audio loop controls style="display: flex;width: 100%;">';
	                            html += '<source id="download" src="'+ response.download +'">';
                                html += '</audio>';
	                            html += '</div>';
	
	                            //вставить потом ссылку на адрес выгрузки #location
	                            $("#downloadCall").append(html);

								
								//download.src = response.download;
								/*
								var x = document.createElement("SOURCE");
								x.setAttribute("src", response.download);
								document.getElementById("download").appendChild(x);
								*/
	
	
	                            formulario.location.value = response.location;
	                            formulario.comment.value = response.comment;
	                            formulario.commentManager.value = response.commentManager;
	                            formulario.km.value = response.km;

	                            formulario.combine.checked = response.combine;
	                            formulario.zamer.checked = response.zamer;
	                            formulario.setka.checked = response.setka;
	                            formulario.increased.checked = response.increased;
	                            formulario.deals.checked = response.deals;
	                            formulario.send_SMS.checked = response.send_SMS;

								formulario.statusObject.value = response.statusObject;


	                            formulario.floor.value = response.floor;
	                            formulario.entrance.value = response.entrance;
	                            formulario.m2.value = response.m2;
	                            formulario.thickness.value = response.thickness;
	                            formulario.m2_price.value = response.m2_price;
	                            formulario.price_styazhka.value = response.price_styazhka; //переписать после переноса
								
	                            
	
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
	
	                            $("#eventModalLabel").html(response.id + '. Дата обновления: ' + response.info_data + ' ' + response.user);
	
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
	

		// Раздел. Доставки. Фильтр по менеджерам.
		function AddManagerFullCalendar() {
			if($("select[id=callSelect]").length < 1) {
				var selectHTML =
					"<select id=\"callSelect\" class=\"fc-myCustomButton-button fc-button fc-button-primary\">" +
						"<option value=''>Все звонки</option>" +
						"<option value='2'>Обработать</option>" +
						"<option value='1'>Уникальные</option>" +
					"</select>";
					
				// depending on where on the bar you want it, use prependTo or appendTo, and alter the array number
				$(selectHTML).appendTo($(".fc-header-toolbar .fc-toolbar-chunk")[0]);
				
				$("#callSelect").on('change', function() {
					// some onchange function
					console.log('callSelect');
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

		
		// Звонки и График. Расстояние от Мкад
	    $("#km").bind("change keyup input click", function() {
	        if (this.value.match(/[^0-9]/g)) {
	            this.value = this.value.replace(/[^0-9]/g, '');
	        }
	    });
		// Звонки и График. Этаж
	    $("#floor").bind("change keyup input click", function() {
	        if (this.value.match(/[^0-9]/g)) {
	            this.value = this.value.replace(/[^0-9]/g, '');
	        }
	    });
		// Звонки и График. Подъезд
	    $("#entrance").bind("change keyup input click", function() {
	        if (this.value.match(/[^0-9]/g)) {
	            this.value = this.value.replace(/[^0-9]/g, '');
	        }
	    });
		// Звонки и График. Площадь
	    $("#m2").bind("change keyup input click", function() {
	        if (this.value.match(/[^0-9]/g)) {
	            this.value = this.value.replace(/[^0-9]/g, '');
	        }
	    });
		// Звонки и График. Слой
	    $("#thickness").bind("change keyup input click", function() {
	        if (this.value.match(/[^0-9]/g)) {
	            this.value = this.value.replace(/[^0-9]/g, '');
	        }
	    });
		// Звонки и График. Цена за м2 работ
	    $("#m2_price").bind("change keyup input click", function() {
	        if (this.value.match(/[^0-9]/g)) {
	            this.value = this.value.replace(/[^0-9]/g, '');
	        }
	    });
		// Звонки и График. Сумма по виду работ
	    $("#price_styazhka").bind("change keyup input click", function() {
	        if (this.value.match(/[^0-9]/g)) {
	            this.value = this.value.replace(/[^0-9]/g, '');
	        }
	    });



		// Звонки и График. Обновление списка сделок через 30 секунд. /// --- Реализовать через историю изменений.
	    setInterval(function () {
	        console.log('calendar.refetchEvents()');
	        document.querySelectorAll('.popover').forEach(e => e.remove());
	        calendar.refetchEvents();

			/*
			$.ajax({
	                url: config.routes.refetch,
	                type: "POST",
	                data: {
	                    id: id,
	                    type: "list"
	                },
	                success: function (data) {
						
	                    $('#eventModal').modal('hide');
	                    calendar.refetchEvents();
	
	                    console.log('success сохранение');
	
	                },
	                error: function (xhr, status, error) {
	                    console.error(xhr.responseText);
	                    alert("Ошибка сохранения. Пожалуйста проверьте поля.");
	                }
	            });
			*/

	
	    }, 100000);
	
		// Звонки и График. Сохранить изменения в карточке звонка.
	        $('#saveEventBtn').on('click', function () {
	            console.log('saveEventBtn');
	            
	            var price = $('#price').val();             
	
				/*
	            if (!price || price == 0) {
	                console.log('Вывод price: ', price);
	
	                const messageText = "Не заполнена сумма";
	                const message = document.createElement('div');
	                message.className = 'alert alert-danger alert-dismissible fade show';
	                message.innerHTML = `${messageText}<button type="button" class="close" data-dismiss="alert">×</button>`;
	                messages.appendChild(message);
	
	                return false;
	            }
				*/
	
	
	            var id = $('#id').val();
	            var type = $('#type').val();
	            var sourceClient = $('#sourceClient').val();
	            var nameContacts = $('#nameContacts').val();
	            var phoneContacts = $('#phoneContacts').val();
	
	            var nameContacts2 = $('#nameContacts2').val();
	            var phoneContacts2 = $('#phoneContacts2').val();
	
	            var urgent = $('#urgent').is(':checked') ? 1 : 0;
	            var BN = $('#BN').is(':checked') ? 1 : 0;
	            var nomer2 = $('#nomer2').is(':checked') ? 1 : 0;
	
	            var location = $('#location').val();
	            var comment = $('#comment').val();
	            var commentManager = $('#commentManager').val();
	            var km = $('#km').val();km
	            //var distance = $('#distance').val();
	            var statusID = $('#statusID').val();
	            var statusObject = $('#statusObject').val();
	            //var amount = $('#amount').val();
	
	            var start = moment($('#startDateTime').val()).format('YYYY-MM-DD');
	            var end = moment($('#endDateTime').val()).format('YYYY-MM-DD');


	            var combine = $('#combine').is(':checked') ? 1 : 0;
	            var zamer = $('#zamer').is(':checked') ? 1 : 0;
	            var setka = $('#setka').is(':checked') ? 1 : 0;
	            var increased = $('#increased').is(':checked') ? 1 : 0;
	            var deals = $('#deals').is(':checked') ? 1 : 0;
	            var send_SMS = $('#send_SMS').is(':checked') ? 1 : 0;


	            var floor = $('#floor').val();
	            var entrance = $('#entrance').val();
	            var m2 = $('#m2').val();
	            var thickness = $('#thickness').val();
	            var m2_price = $('#m2_price').val();
	            var price_styazhka = $('#price_styazhka').val();
	
	            $.ajax({
	                url: config.routes.action,
	                type: "POST",
	                data: {
	                    id: id,
	                    start: start,
	                    end: end,
	                    sourceClient: sourceClient,
	                    urgent: urgent,
	                    BN: BN,
	                    nomer2: nomer2,
	                    nameContacts: nameContacts,
	                    phoneContacts: phoneContacts,
	                    nameContacts2: nameContacts2,
	                    phoneContacts2: phoneContacts2,
	                    location: location,
	                    comment: comment,
	                    commentManager: commentManager,
	                    km: km,
	                    //distance: distance,
	                    statusID: statusID,
	                    statusObject: statusObject,
	                    combine: combine,
	                    zamer: zamer,
	                    setka: setka,
	                    increased: increased,
	                    deals: deals,
	                    send_SMS: send_SMS,
	                    floor: floor,
	                    entrance: entrance,
	                    m2: m2,
	                    thickness: thickness,
	                    m2_price: m2_price,
	                    price_styazhka: price_styazhka,
	                    price: price,
	                    //amount: amount,
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




			// Звонки и График. Загрузка типов сделок при загрузки страницы.
			$.ajax({
	        url: config.routes.select,
	        type: 'get',
	        data: {
	            type: "statusObject"
	        },
	        success: function (data) {
	            console.log('statusObject', data);
	            
	            data.forEach((item) => {
	                $('#statusObject').append('<option value="' + item.id + '">' + item.name + '</option>');
	            });
	        }
	    });



</script>



{!! HTML::script('assets/js/as/calendar.js') !!}

@stop
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="assets/css2/font-awesome.css" rel="stylesheet" />
<style>


	@media (max-width:991px){
		.pl-4, .px-4 {
			padding-left: 0 !important;
			padding-right: 0 !important;
		}

		.fc .fc-view-harness {
			height: 600px !important;
		}
	}
	/*
	.fc-list-event-time {
		display:none;
	}
	.fc-scrollgrid-section-liquid {
		display:none;
	}
	*/

	.sourceClient {
		border-radius: 4px;
		border-left-style: solid;
		border-left-width: 6px;
	}

	/*
	.fc-h-event {
		border: 0px solid var(--fc-event-border-color);
	}
	*/

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
		#calendar .fc-header-toolbar button, #calendar .fc-header-toolbar select {
			margin: 1px 1px;
			/* padding: 1px 1px; */
			width: 98%;
		}
		.fc .fc-button-group {
			display: inline-table;
		}

		.fc .fc-toolbar-title {
			display: none;
		}

		.fc-direction-ltr .fc-daygrid-event.fc-event-end, .fc-direction-rtl .fc-daygrid-event.fc-event-start {
			margin-left: 0px;
			margin-right: 0px;
			line-height: 2.5;
		}

	}

	.fc-direction-ltr .fc-daygrid-event.fc-event-end, .fc-direction-rtl .fc-daygrid-event.fc-event-start {
		line-height: 2.0;
	}

</style>
@stop