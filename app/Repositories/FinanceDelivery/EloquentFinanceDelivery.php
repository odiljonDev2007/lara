<?php

namespace Vanguard\Repositories\FinanceDelivery;

use Illuminate\Database\Eloquent\Collection;
use Vanguard\Events\User\LoggedUserActivity;

use Carbon\Carbon;
use DB;
use Vanguard\User;
use Vanguard\Delivery;
use Vanguard\Contacts;
use Vanguard\StatusID;
use Vanguard\HistoryDelivery;
use Vanguard\GuideTransport;

class EloquentFinanceDelivery implements FinanceDeliveryRepository
{
    public function all($status = null, $statusDelete = null, $id_manager = null, $bn = null, $mercenary = null, $id_driver = null,  $startDate = null, $endDate = null): Collection
    {
        //dd(Delivery::all());

        $query = Delivery::query();

        if ($id_manager) {
            $query->where('delivery.id_manager', $id_manager);
        }
        if ($id_driver) {
            $query->where('delivery.id_driver', $id_driver);
        }
        if ($status) {
            $query->where('delivery.statusID', $status);
        }

        if ($statusDelete) {
            $query->where('delivery.status', $statusDelete);
        } else if ($statusDelete == '2') {
            $query->where('delivery.status', $statusDelete);
        } else {
            $query->whereIn('delivery.status', ['1', '2']);
        }
        

        if ($bn) {
            $query->where('delivery.BN', $bn);
        } else if ($bn == '0') {
            $query->where('delivery.BN', $bn);
        } else {
            $query->whereIN('delivery.BN', ['0','1']);
        }

        if ($mercenary) {
            $query->where('c2.mercenary', $mercenary);
        } else if ($mercenary == '0') {
            $query->where('c2.mercenary', $mercenary);
        } else {
            $query->whereIN('c2.mercenary', ['0','1']);
        }

        if ($startDate == null & $endDate == null) {
            
            $startOfCurrentWeek = Carbon::now()->startOfWeek();
            $startOfLastWeek  = $startOfCurrentWeek->copy()->subDays(7);
            
            $endOfCurrentWeek = Carbon::now()->endOfWeek();
        
            $startDate = $startOfLastWeek;
            $endDate = $endOfCurrentWeek;
        } else {
            $startDate = Carbon::parse($startDate)->format('Y-m-d');
            $endDate = Carbon::parse($endDate)->format('Y-m-d');
        }

        if ($startDate) {
            $query->where('delivery.start', '>=', $startDate);
        }
        if ($endDate) {
            $query->where('delivery.start', '<=', $endDate." 23:59:59");
        }

        $financeDelivery = $query->leftJoin('statusID', 'delivery.statusID', '=', 'statusID.id')
            ->leftJoin('users as c1', 'delivery.id_manager', '=', 'c1.id')
            ->leftJoin('guideTransport as c2', 'delivery.id_driver', '=', 'c2.id')
            //->whereIn('delivery.status', ['1', '2'])
            ->select('delivery.*', 'delivery.id', 'delivery.id as deliveryID', 'delivery.title', 'delivery.statusID',
                'delivery.phoneContacts', 'delivery.distance', 'delivery.location',
                'delivery.BN', 'delivery.urgent', 'delivery.loadingAddress',
                'delivery.comment', 'delivery.commentManager', 'delivery.start', 'delivery.end',
                'delivery.status', 'c2.mercenary as mercenary',
                'statusID.name as statusName', 'statusID.color as color', 'delivery.user_original', 
                'c1.abb as abbManager', DB::raw('CONCAT(c1.first_name, " ", c1.last_name) AS managerName'),
                DB::raw('CONCAT(c2.marka, " ", c2.nomer, " ", c2.mercenaryName) AS driverName'))
                ->orderBy('delivery.start', 'DESC')
                //->orderBy('delivery.id_manager', 'ASC')  
            ->get();
            //->first(216);
        
        //dd($financeDelivery);

        return $financeDelivery;
    }


    public function id_manager($column = 'first_name', $key = 'id') {
        
        $query = User::query();
        
        $result = $query
            ->whereIn('role_id', ['1', '3', '4'])
            ->where('status', 'Active')
            ->select(DB::raw('CONCAT(first_name, " ", last_name) AS name, id'))
            ->orderBy('id', 'DESC')
            ->pluck('name', $key);
        
        return $result;
    }

    public function id_driver($column = 'nomer', $key = 'id') {
        
        $query = GuideTransport::query();
        
        $result = $query->select(DB::raw('CONCAT(marka, " ", nomer, " ", mercenaryName) AS name, id'))
            ->pluck('name', $key);
        
        return $result;
    }
    
    public function status($column = 'first_name', $key = 'id') {
        
        $query = statusID::query();
        
        $result = $query->pluck('name', $key);
        
        return $result;
    }

    public function find($id): ?FinanceDelivery
    {
        return FinanceDelivery::find($id);
    }

    public function create(array $data): FinanceDelivery
    {
        //dd($data);
        if ($data['mercenaryName'] == null) {
            $data['mercenaryName'] = '';
        }
        $FinanceDelivery = FinanceDelivery::create($data);

        //event(new Created($GuideTransport));
        event(new LoggedUserActivity);

        return $FinanceDelivery;
    }

    public function update($id, array $data): FinanceDelivery
    {
        if ($data['mercenaryName'] == null) {
            $data['mercenaryName'] = '';
        }
        $FinanceDelivery = $this->find($id);

        $FinanceDelivery->update($data);

        //event(new Updated($GuideTransport));
        event(new LoggedUserActivity);

        return $FinanceDelivery;
    }

    public function delete($id): void
    {
        $FinanceDelivery = $this->find($id);
        
        //event(new Deleted($FinanceDelivery));
        event(new LoggedUserActivity);

        $FinanceDelivery->delete();
    }

    public function lists(string $column = 'name', string $key = 'id'): \Illuminate\Support\Collection
    {
        return FinanceDelivery::pluck($column, $key);
    }

    public function findByName($name): ?FinanceDelivery
    {
        return FinanceDelivery::where('name', $name)->first();
    }
}
