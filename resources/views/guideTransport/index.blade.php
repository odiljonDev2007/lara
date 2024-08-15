@extends('layouts.app')

@section('page-title', __('GuideTransports'))
@section('page-heading', __('GuideTransports'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('GuideTransports')
    </li>
@stop

@section('content')

    @include('partials.messages')

    <div class="card">
        <div class="card-body">
            <div class="row mb-3 pb-3 border-bottom-light">
                <div class="col-lg-12">
                    <div class="float-right">
                        <a href="{{ route('guideTransport.create') }}" class="btn btn-primary btn-rounded">
                            <i class="fas fa-plus mr-2"></i>
                            @lang('Add Guide Transports')
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" id="users-table-wrapper">
                <table class="table table-striped table-borderless">
                    <thead>
                    <tr>
                        <th class="min-width-100">@lang('Guide Transports Marka')</th>
                        <th class="min-width-150">@lang('Guide Transports Nomer')</th>
                        <th class="min-width-150">@lang('Phone')</th>
                        <th class="min-width-150">@lang('Guide Transports Address')</th>
                        <th class="min-width-150">@lang('Guide Transports Mercenary')</th>
                        <th class="min-width-150">@lang('Guide Transports MercenaryName')</th>
                        <th class="text-center">@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if (count($guideTransports))
                            @foreach ($guideTransports as $guideTransport)
                                <tr>
                                    <td>{{ $guideTransport->marka }}</td>
                                    <td>{{ $guideTransport->nomer }}</td>
                                    <td>{{ $guideTransport->phoneContacts }}</td>
                                    <td>{{ $guideTransport->address }}</td>
                                    <td>{!! $guideTransport->mercenary ? '<span style="color:green">Да</span>' : '<span style="color:red">Нет</span>' !!}</td>
                                    <td>{{ $guideTransport->mercenaryName }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('guideTransport.edit', $guideTransport) }}" class="btn btn-icon"
                                           title="@lang('Edit Guide Transports')" data-toggle="tooltip" data-placement="top">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                            <a href="{{ route('guideTransport.destroy', $guideTransport) }}" class="btn btn-icon"
                                               title="@lang('Delete Guide Transports')"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               data-method="DELETE"
                                               data-confirm-title="@lang('Please Confirm')"
                                               data-confirm-text="@lang('Are you sure that you want to delete this role?')"
                                               data-confirm-delete="@lang('Yes, delete it!')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5"><em>@lang('No records found.')</em></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
