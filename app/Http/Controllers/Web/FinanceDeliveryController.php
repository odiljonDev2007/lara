<?php

namespace Vanguard\Http\Controllers\Web;

//use Cache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Vanguard\Repositories\FinanceDelivery\FinanceDeliveryRepository;
use Vanguard\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Auth;
use Vanguard\User;
use Vanguard\Delivery;
use Vanguard\Contacts;
use Vanguard\StatusID;
use Vanguard\HistoryDelivery;
use Vanguard\GuideTransport;
use Vanguard\Events\User\LoggedUserActivity;

class FinanceDeliveryController extends Controller
{
    public function __construct(private readonly FinanceDeliveryRepository $financeDelivery)
    {
        $this->middleware('auth');
    }

    private function mercenary() {
        return [0 => 'Нет'] + [1 => 'Да'];
    }

    public function index(Request $request): View
    {
        $status = [0 => 'Статус'] + $this->financeDelivery->status()->toArray();
        $bn = ['' => 'Все объекты'] + [1 => 'Б/н'] + [0 => 'Наличные'];
        $mercenary = ['' => 'Весь транспорт'] + [1 => 'Наемники'] + [0 => 'Личный транспорт'];
        $id_manager = [0 => 'Все менеджеры'] + $this->financeDelivery->id_manager()->toArray();
        $id_driver = [0 => 'Список машин'] + $this->financeDelivery->id_driver()->toArray();
        $statusDelete = ['' => 'Все сделки'] + [1 => 'Без пометки на удаление'] + [2 => 'С пометкой на удаление'];


        
        $financeDelivery = $this->financeDelivery->all(
            $request->status,
            $request->statusDelete,
            $request->id_manager,
            $request->bn,
            $request->mercenary,
            $request->id_driver,
            $request->startDate,
            $request->endDate
        );

        $total = 0;
        $sumPrice = 0;

        foreach ($financeDelivery as $key => $value) {
            $sumPrice = $sumPrice + $financeDelivery[$key]['price'];
            $total = $total + 1;

            //все равно перебираем, добавляем уникальные даты в отдельное поле без времени
            $financeDelivery[$key]['startUnique'] = Carbon::parse($financeDelivery[$key]['start'])->format('Y-m-d');

        }

        // проверка дат на уникальность
        $startUnique = $financeDelivery->unique('startUnique');
        $startUnique->all();
        
        //dd($startUnique->count());

        return view('financeDelivery.index', [
            'financeDelivery' => $financeDelivery,
            'status' => $status,
            'statusDelete' => $statusDelete,
            'bn' => $bn,
            'mercenary' => $mercenary,
            'id_manager' => $id_manager,
            'id_driver' => $id_driver,
            'total' => $total,
            'sumPrice' => $sumPrice,
            'startUnique' => $startUnique->count()
        ]);
    }

    public function create(): View
    {


        return view('financeDelivery.add-edit', [
            'mercenary' => $this->mercenary(),
            'edit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->financeDelivery->create($request->all());

        return redirect()->route('financeDelivery.index')
            ->withSuccess('Машина успешно добавлена.');
    }

    public function edit(FinanceDelivery $financeDelivery): View
    {
        return view('financeDelivery.add-edit', [
            'financeDelivery' => $financeDelivery,
            'mercenary' => $this->mercenary(),
            'edit' => true,
        ]);
    }
    
    public function update(FinanceDelivery $financeDelivery, Request $request): RedirectResponse
    {
        $this->financeDelivery->update($financeDelivery->id, $request->all());

        return redirect()->route('financeDelivery.index')
            ->withSuccess('Машина успешно обновлена.');
    }

    public function destroy($FinanceDelivery): RedirectResponse
    {
        
        $this->financeDelivery->delete($financeDelivery);

        Cache::flush();

        return redirect()->route('financeDelivery.index')
            ->withSuccess('Машина успешно удалена.');
    }

}
