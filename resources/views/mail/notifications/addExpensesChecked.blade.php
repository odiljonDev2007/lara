<p>{{ $type }} в принятом объекте: <a href="{{ route('objects.expenses', $object) }}">{{ $object }}</a> от {{ $date_added }} из категории ({{ $category_name }}) у пользователя: <b>{{ $addUser }}</b>.
<p>Сумма: {{ $amount }} </p>
@if ($volume)
    <p>Количество: {{ $volume }} </p>
@endif
@if ($comment)
    <p>Коментарий: {{ $comment }}</p>
@endif

<a href="{{ route('objects.expenses', $object) }}">подробнее по ссылке...</a> <br/><br/>

@lang('app.regards'), <br/>
{{ settings('app_name') }}