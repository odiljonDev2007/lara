<?php

namespace Vanguard\Http\Controllers\Web;

use Cache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Vanguard\Repositories\GuideTransport\GuideTransportRepository;
use Vanguard\GuideTransport;
use Vanguard\Http\Controllers\Controller;

class GuideTransportController extends Controller
{
    public function __construct(private readonly GuideTransportRepository $guideTransports)
    {
        $this->middleware('auth');
    }

    private function mercenary() {
        return [0 => 'Нет'] + [1 => 'Да'];
    }

    public function index(): View
    {
        return view('guideTransport.index', [
            'guideTransports' => $this->guideTransports->all()
        ]);
    }

    public function create(): View
    {


        return view('guideTransport.add-edit', [
            'mercenary' => $this->mercenary(),
            'edit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->guideTransports->create($request->all());

        return redirect()->route('guideTransport.index')
            ->withSuccess('Машина успешно добавлена.');
    }

    public function edit(GuideTransport $guideTransport): View
    {
        return view('guideTransport.add-edit', [
            'guideTransport' => $guideTransport,
            'mercenary' => $this->mercenary(),
            'edit' => true,
        ]);
    }
    
    public function update(GuideTransport $guideTransport, Request $request): RedirectResponse
    {
        $this->guideTransports->update($guideTransport->id, $request->all());

        return redirect()->route('guideTransport.index')
            ->withSuccess('Машина успешно обновлена.');
    }

    public function destroy($guideTransport): RedirectResponse
    {
        
        $this->guideTransports->delete($guideTransport);

        Cache::flush();

        return redirect()->route('guideTransport.index')
            ->withSuccess('Машина успешно удалена.');
    }

}
