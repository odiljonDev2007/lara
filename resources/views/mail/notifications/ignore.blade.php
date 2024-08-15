<p>{{ $type }} в объекте: <a href="{{ route('objects.expenses', $object) }}">{{ $object }}</a> от {{ $date_added }} из категории ({{ $category_name }}) у пользователя: <b>{{ $toUser_full_name }}</b> требует уточнения.
<p>Сумма: {{ $amount }} </p>
@if ($volume)
    <p>Количество: {{ $volume }} </p>
@endif
@if ($comment)
    <p>Коментарий: {{ $comment }}</p>
@endif
<a href="{{ route('expensesReceiptsFirma.list', ['moneyTransfer' => '32', 'ignore' => '1']) }}">подробнее по ссылке...</a> <br/><br/>

@lang('app.regards'), <br/>
{{ settings('app_name') }}, {{ $fromUser }}