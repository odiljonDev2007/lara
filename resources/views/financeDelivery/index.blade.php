@extends('layouts.app')

@section('page-title', __('FinanceDelivery'))
@section('page-heading', __('FinanceDelivery'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('FinanceDelivery')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <form action="" method="GET" id="finance-form" class="pb-2 mb-3 border-bottom-light">
                <div class="row my-3 flex-md-row flex-column-reverse">    
                    <div class="col-md-2 mt-2 mt-md-0 input-daterange">
                        <input type="text" name="startDate" id="startDate" class="startDate" value="" readonly style="cursor: pointer;"/>
                    </div>
                    <div class="col-md-2 mt-2 mt-md-0 input-daterange">
                        <input type="text" name="endDate" id="endDate" class="endDate" value="" readonly style="cursor: pointer;"/>
                    </div> 
                    <div class="col-md-2 mt-2 mt-md-0 input-daterange">
                        <button id="show-period" class="btn btn-secondary">Показать период</button>
                    </div> 


                    <div class="col-md-2 mt-2 mt-md-0">
                        {!! Form::select('id_manager', $id_manager, Request::get('id_manager'), ['id' => 'id_manager', 'class' => 'form-control input-solid']) !!}
                    </div>
                    <div class="col-md-2 mt-2 mt-md-0">
                        {!! Form::select('bn', $bn, Request::get('bn'), ['id' => 'bn', 'class' => 'form-control input-solid']) !!}
                    </div>
                    <div class="col-md-2 mt-2 mt-md-0">
                        {!! Form::select('status', $status, Request::get('status'), ['id' => 'status', 'class' => 'form-control input-solid']) !!}
                    </div>


                    <div class="col-md-2 mt-2 mt-md-0">
                        {!! Form::select('mercenary', $mercenary, Request::get('mercenary'), ['id' => 'mercenary', 'class' => 'form-control input-solid']) !!}
                    </div>
                    <div class="col-md-2 mt-2 mt-md-0">
                        {!! Form::select('id_driver', $id_driver, Request::get('id_driver'), ['id' => 'id_driver', 'class' => 'form-control input-solid']) !!}
                    </div>
                    <div class="col-md-2 mt-2 mt-md-0">
                        {!! Form::select('statusDelete', $statusDelete, Request::get('statusDelete'), ['id' => 'statusDelete', 'class' => 'form-control input-solid']) !!}
                    </div>
                </div>
            </form>

            <div class="table-responsive table-scroll" id="users-table-wrapper" data-mdb-perfect-scrollbar="true" style="position: relative; height: 800px">
                <table class="table table-striped table-borderless">
                    <thead style="top: 0;position: sticky;">
                    <tr>
                        <th scope="col" class="min-width-150 financeTH">Дата</th>
                        <th scope="col" class="min-width-150 financeTH">ИД</th>
                        <th scope="col" class="min-width-150 financeTH">Наименование</th>
                        <th scope="col" class="min-width-150 financeTH">Статус</th>
                        <th scope="col" class="min-width-150 financeTH">Менеджер</th>
                        <th scope="col" class="min-width-150 financeTH">Сумма</th>
                        <th scope="col" class="min-width-150 financeTH">Вес (кг)</th>
                        <th scope="col" class="min-width-150 financeTH">Наемник</th>
                        <th scope="col" class="min-width-80 financeTH">Водитель</th>
                        <th scope="col" class="min-width-150 financeTH">Безнал</th>
                        <!--<th class="text-center">@lang('Action')</th>-->
                    </tr>
                    </thead>
                    <tbody>
                        @if (count($financeDelivery))
                            @foreach ($financeDelivery as $financeDeliverys)
                                <tr class="deliverys-status-{{ $financeDeliverys->status }}">
                                    <td class="financeTD">{{ \Carbon\Carbon::parse($financeDeliverys->start)->isoFormat('D.MM') }}
                                    ({{ \Carbon\Carbon::parse($financeDeliverys->start)->isoFormat('dd') }}) </br>
                                    ({{ \Carbon\Carbon::parse($financeDeliverys->start)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($financeDeliverys->end)->format('H:i') }})</td>
                                    <td class="financeTD">
                                    <a href="{{ route('calendarDelivery.index', ['id' => $financeDeliverys->id]) }}" title="Открыть в системе доставок" target="_blank">
                                    <i class="fas fa-calendar" aria-hidden="true"></i> {{ $financeDeliverys->id }}</a>
                                    </td>
                                    <td class="financeTD">{{ $financeDeliverys->title }}</td>
                                    <td class="financeTD">
                                    <span class="badge badge-lg badge-status-{{ $financeDeliverys->statusID }}">
                                        {{ $financeDeliverys->statusName }}
                                    </td>
                                    <td class="financeTD">{{ $financeDeliverys->managerName }}</td>
                                    <td class="financeTD">{{ number_format($financeDeliverys->price, 0, '.', ' ') }}р.</td>
                                    <td class="financeTD">{{ $financeDeliverys->tone }}</td>
                                    <td class="financeTD">{!! $financeDeliverys->mercenary ? '<span style="color:green">Да</span>' : '<span style="color:red">Нет</span>' !!}</td>
                                    <td class="financeTD">{{ $financeDeliverys->driverName }}</td>
                                    <td class="financeTD">{!! $financeDeliverys->BN ? '<span style="color:green">Да</span>' : '<span style="color:red">Нет</span>' !!}</td>
                                </tr>
                            @endforeach
                            <tr style="border-bottom: 3px solid #a0a2a4; border-top: 3px solid #a0a2a4;">
                                <td style="white-space: nowrap; border: 3px solid #a0a2a4;">Всего доставок: {{ number_format($total, 0, '.', ' ') }}</td>
                                <td colspan="4" style="white-space: nowrap;"></td>
                                <td style="white-space: nowrap; border: 3px solid #a0a2a4;">{{ number_format($sumPrice, 0, '.', ' ') }}р.</td>
                                </tr>
                            <tr style="border-bottom: 3px solid #a0a2a4; border-top: 3px solid #a0a2a4;">
                                <td style="white-space: nowrap; border: 3px solid #a0a2a4;">Всего отработанных дней: <span style="color: #029022;font-weight: 800;">{{ number_format($startUnique, 0, '.', ' ') }}</span></td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="11"><em>Нет результатов...</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop


@section('styles')

    {!! HTML::style('assets/css/jquery-ui.css') !!}   
    
    <link media="all" type="text/css" rel="stylesheet" href="{{ url('assets/css/daterangepicker.css') }}">

    <style>
    
    .table-responsive table th {
        position: -webkit-sticky;
        position: sticky;
        top: 0;
    }
        
        .daterange, .startDate, .endDate {
            width: 100%;
            padding: 6px 10px;
            margin-right: 5px;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
    
        .financeTH {
            white-space: nowrap;
            position: sticky;
            top: 0;
            background-color: white;
        }

        .financeTD {
            white-space: nowrap;
            border-right: 1px solid #e5e5e5;
        }

        .badge-status-1 {
            color: #fff;
            background-color: #3faf1b
        }

        .badge-status-2 {
            color: #fff;
            background-color: #00a9ff
        }

        .badge-status-3 {
            color: #fff;
            background-color: #FFC31D
        }

        .badge-status-4 {
            color: #fff;
            background-color: #d0cccc
        }

        .badge-status-5 {
            color: #fff;
            background-color: #f72323
        }

        .badge-status-6 {
            color: #fff;
            background-color: #0a0a0a
        }

        .badge-status-22 {
            color: #fff;
            background-color: #04c
        }
        
    </style>
@stop

@section('scripts')


    {!! HTML::script('assets/js/moment.min.js') !!}
    {!! HTML::script('assets/js/daterangepicker.js') !!}
    {!! HTML::script('assets/js/jquery-ui.min.js') !!}  

    {!! HTML::script('assets/js/ru.js') !!}
    
    <script>
		$("#id_manager").change(function () {
            $("#finance-form").submit();
        });
        $("#status").change(function () {
            $("#finance-form").submit();
        });
        $("#id_driver").change(function () {
            $("#finance-form").submit();
        });
        $("#bn").change(function () {
            $("#finance-form").submit();
        });
        $("#mercenary").change(function () {
            $("#finance-form").submit();
        });
        $("#statusDelete").change(function () {
            $("#finance-form").submit();
        });
        
        
        
        /* Локализация datepicker */
        $.datepicker.regional['ru'] = {
        	closeText: 'Закрыть',
        	prevText: 'Предыдущий',
        	nextText: 'Следующий',
        	currentText: 'Сегодня',
        	monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
        	monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
        	dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
        	dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
        	dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        	weekHeader: 'Не',
        	dateFormat: 'dd.mm.yy',
        	firstDay: 1,
        	isRTL: false,
        	showMonthAfterYear: false,
        	yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['ru']);
        
        var urlVar = window.location.search; // получаем параметры из урла
        console.log("urlVar", urlVar);
        
        var arrayVar = []; // массив для хранения переменных
        var valueAndKey = []; // массив для временного хранения значения и имени переменной
                    
        arrayVar = (urlVar.substr(1)).split('&'); // разбираем урл на параметры
                    
        console.log("arrayVar", arrayVar);
                    
        //if(arrayVar[0]=="") return false; // если нет переменных в урле
        /* */           
        for (i = 0; i < arrayVar.length; i ++) { // перебираем все переменные из урла
            valueAndKey = arrayVar[i].split('='); // пишем в массив имя переменной и ее значение
            if (valueAndKey[0] == "startDate") {
                            
                if (valueAndKey[1] != "") {
                    var date1 = valueAndKey[1];
                    console.log('date1',date1);
                    //$('#startDate').datepicker().val(date1);
                } else {
                    //var date1 = moment().subtract(1, 'weeks').startOf('isoWeek');
                }
                
            } else if (valueAndKey[0] == "endDate") {
                            
                if (valueAndKey[1] != "") {
                    var date2 = valueAndKey[1];
                    
                    console.log('date2',date2);
                    //$('#endDate').datepicker().val(date2);
                    // $("#daterange").data('daterangepicker').endDate.format('YYYY-MM-DD');
                    
                } else {
                    //var date2 = moment().subtract(0, 'weeks').endOf('isoWeek');
                }
                
            } else if (valueAndKey[0] == "search") {
                
                console.log("valueAndKey[1]", valueAndKey[1]);
                            
            }
                        
        }
        
        console.log("date1", date1);
            
            if (typeof date1 == "undefined" & typeof date2 == "undefined") {
                
                var date1 = moment().subtract(1, 'weeks').startOf('isoWeek');
                var date2 = moment().subtract(0, 'weeks').endOf('isoWeek');
                
                console.log("date1", date1);
                console.log("date2", date2);
                
                //$("#finance-form").submit();
                date1 = moment(date1).format('DD.MM.YYYY');
                date2 = moment(date2).format('DD.MM.YYYY');
                
            } 
        
        //date1 = moment(date1).format('DD.MM.YYYY');
            //date2 = moment(date2).format('DD.MM.YYYY');

            
            $('#endDate').datepicker({
                //todayBtn:'linked',
                dateFormat:'dd.mm.yy'
                //autoclose:true
                
            }).val(date2);
            
            $("#startDate").datepicker({
    			dateFormat: 'dd.mm.yy',
    			onSelect: function (dateStr) {
    				var end_max = $("#endDate").datepicker({
    					dateFormat: 'dd.mm.yyyy'
    				}).val();
    				console.log('end_max < this.value', end_max < this.value);
    				if (end_max < this.value) {
    					$('#endDate').val(this.value);
    					$("#endDate").datepicker("option", {
    						dateFormat: 'dd.mm.yy',
    						minDate: dateStr,
    					})
    				} else {
    					$("#endDate").datepicker("option", {
    						dateFormat: 'dd.mm.yy',
    						minDate: dateStr,
    					})
    				}
    			}
    		}).val(date1);
            
            //$("#time-start").datepicker("getDate");
            
            
            $('#show-period').on('click', function(e) {
                //showDate();
                console.log("startDate", $("#startDate").datepicker("getDate"));
                
            });
        
        
        
    </script>
@stop
