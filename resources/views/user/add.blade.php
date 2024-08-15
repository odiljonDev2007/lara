@extends('layouts.app')

@section('page-title', __('Add User'))
@section('page-heading', __('Create New User'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">@lang('Users')</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Create')
    </li>
@stop

@section('content')

@include('partials.messages')

{!! Form::open(['route' => 'users.store', 'files' => true, 'id' => 'user-form']) !!}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('User Details')
                    </h5>
                    <p class="text-muted font-weight-light">
                        @lang('A general user profile information.')
                    </p>
                </div>
                <div class="col-md-9">
                    @include('user.partials.details', ['edit' => false, 'profile' => false])
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <h5 class="card-title">
                        @lang('Login Details')
                    </h5>
                    <p class="text-muted font-weight-light">
                        @lang('Details used for authenticating with the application.')
                    </p>
                </div>
                <div class="col-md-9">
                    @include('user.partials.auth', ['edit' => false])
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">
                @lang('Create User')
            </button>
        </div>
    </div>
{!! Form::close() !!}

<br>
@stop

@section('scripts')
    {!! HTML::script('assets/js/as/profile.js') !!}
    {!! JsValidator::formRequest('Vanguard\Http\Requests\User\CreateUserRequest', '#user-form') !!}


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


    </script>


@stop