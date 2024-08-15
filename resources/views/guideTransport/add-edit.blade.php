@extends('layouts.app')

@section('page-title', __('GuideTransports'))
@section('page-heading', $edit ? $guideTransport->name : __('Create New GuideTransport'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('guideTransport.index') }}">@lang('GuideTransports')</a>
    </li>
    <li class="breadcrumb-item active">
        {{ __($edit ? 'Edit' : 'Create') }}
    </li>
@stop

@section('content')

@include('partials.messages')

@if ($edit)
    {!! Form::open(['route' => ['guideTransport.update', $guideTransport], 'method' => 'PUT', 'id' => 'guideTransport-form']) !!}
@else
    {!! Form::open(['route' => 'guideTransport.store', 'id' => 'guideTransport-form']) !!}
@endif

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <h5 class="card-title">
                    @lang('GuideTransport Details')
                </h5>
                <p class="text-muted">
                    @lang('A general role information.')
                </p>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label for="marka">@lang('Guide Transports Marka')</label>
                    <input type="text"
                           class="form-control input-solid"
                           id="marka"
                           name="marka"
                           placeholder="@lang('Guide Transports Marka')"
                           value="{{ $edit ? $guideTransport->marka : old('marka') }}">
                </div>
                <div class="form-group">
                    <label for="nomer">@lang('Guide Transports Nomer')</label>
                    <input type="text"
                           class="form-control input-solid"
                           id="nomer"
                           name="nomer"
                           placeholder="@lang('Guide Transports Nomer')"
                           value="{{ $edit ? $guideTransport->nomer : old('nomer') }}">
                </div>
                <div class="form-group">
                    <label for="phoneContacts">@lang('Phone')</label>
                    <input type="text"
                           class="form-control input-solid"
                           id="phoneContacts"
                           name="phoneContacts"
                           placeholder="@lang('Phone')"
                           value="{{ $edit ? $guideTransport->phoneContacts : old('phoneContacts') }}">
                </div>
                <div class="form-group">
                    <label for="address">@lang('Guide Transports Address')</label>
                    <input type="text"
                           class="form-control input-solid"
                           id="address"
                           name="address"
                           placeholder="@lang('Guide Transports Address')"
                           value="{{ $edit ? $guideTransport->address : old('address') }}">
                </div>

                <div class="form-group">
                    <label for="mercenary">@lang('Guide Transports Mercenary')</label>
                    {!! Form::select('mercenary', $mercenary, $edit ? $guideTransport->mercenary : '', ['class' => 'form-control input-solid', 'id' => 'mercenary']) !!}
                </div>

                <div class="form-group mercenaryNames" style="display: none;">
                    <label for="mercenaryName">@lang('Guide Transports MercenaryName')</label>
                    <input type="text"
                           class="form-control input-solid"
                           id="mercenaryName"
                           name="mercenaryName"
                           placeholder="@lang('Guide Transports MercenaryName')"
                           value="{{ $edit ? $guideTransport->mercenaryName : old('mercenaryName') }}">
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">
    {{ __($edit ? 'Update Guide Transports' : 'Create Guide Transports') }}
</button>

@stop

@section('scripts')
    @if ($edit)
    
    @else

    @endif

    <script>
    // инициализация карт
		ymaps.ready(init);

		function init() {

			var suggestView = new ymaps.SuggestView('address', {
				boundedBy: [
					[53.38008177, 34.04055003],
					[57.78053234, 40.89601878]
				],
				// - искать только в этой области
				strictBounds: true,
				// - требуемое количество результатов
				results: 10,
				provider: {
					suggest: function (request, options) {

						var parseItems = ymaps.suggest(request).then(function (items) {
							for (var i = 0; i < items.length; i++) {

								var displayNameArr = items[i].displayName.split(',');
								var displayNameArrValue = items[i].value.split(',');


								var newDisplayName = [];
								for (var j = 0; j < displayNameArr.length; j++) {
									if (displayNameArr[j].indexOf('Россия') == -1) {
										newDisplayName.push(displayNameArr[j]);
									}
								}
								items[i].displayName = newDisplayName.join();


								var newDisplayNameValue = [];
								for (var j = 0; j < displayNameArrValue.length; j++) {
									if (displayNameArrValue[j].indexOf('Россия') == -1) {
										newDisplayNameValue.push(displayNameArrValue[j]);
									}
								}
								items[i].value = newDisplayNameValue.join();


							}

							console.log('items', items);

							return items;
						});

						//console.log('parseItems', parseItems);

						return parseItems;


					}
				}
			});


		}


        $("#phoneContacts").bind("change keyup input click", function() {
			if (this.value.match(/[^0-9]/g)) {
				this.value = this.value.replace(/[^0-9]/g, '');
			}
		});

        if ($("#mercenary").val() == '1') {
            $('.mercenaryNames').show();
        } else {
			$('.mercenaryNames').hide();
		}

		$('#mercenary').on('change', function() {
            console.log('mercenary', $("#mercenary").val());
            if ($("#mercenary").val() == '1') {
                $('.mercenaryNames').show();
			
            } else {
				$('.mercenaryNames').hide();
			}



        });


    </script>
@stop
