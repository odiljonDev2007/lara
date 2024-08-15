<p>{{ $toUser }}, оставил заявку на новый объект: {{ $comment }}.</p>

<a href="{{ route('applicationsCalendar.list') }}">@lang('app.applicationsCalendar_opens')</a> <br/><br/>

@lang('app.regards'), <br/>
{{ settings('app_name') }}