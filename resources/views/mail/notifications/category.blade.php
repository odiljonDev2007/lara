<p>Пользователь: {{ $toUser }}, оставил расход из категории: {{ $category_name }}, на сумму: {{ $amount }}.</p>

<a href="{{ route('expensesReceiptsFirma.list', ['category' => $category, 'user' => $user]) }}">@lang('app.email_expense_open')</a> <br/><br/>

@lang('app.regards'), <br/>
{{ settings('app_name') }}