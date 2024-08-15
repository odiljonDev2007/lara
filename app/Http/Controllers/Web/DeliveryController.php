<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;

use Vanguard\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Auth;
use Vanguard\User;
use Vanguard\Delivery;
use Vanguard\Contacts;
use Vanguard\ContactsDelivery;
use Vanguard\StatusID;
use Vanguard\HistoryDelivery;
use Vanguard\GuideTransport;
use Vanguard\Events\User\LoggedUserActivity;

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexFull(Request $request)
    {
        //dd($request);
        if ($request->ajax()) {
            $data = Delivery::whereDate('start', '>=', $request->start)
                ->whereDate('end', '<=', $request->end)
                ->get(['id', 'title', 'teacher', 'classe', 'subject', 'start', 'end', 'color']);
            return response()->json($data);
        }

        return view('delivery.full-calendar');
    }

    public function index(Request $request)
    {   

        $id_user = Auth::id();
        //dd($id_user);
        
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('generalDirector')) {
            $admin = 1;
        } else {
            $admin = 0;
        }

        event(new LoggedUserActivity);

        return view(
            'delivery.index',
            compact('id_user', 'admin')
        );
    }

    public function calendarAjax(Request $request)
    {   
        //event(new LoggedUserActivity);
        /*
        $data = Delivery::leftJoin('statusID', 'delivery.statusID', '=', 'statusID.id')
                ->leftJoin('users as c1', 'delivery.id_manager', '=', 'c1.id')
                ->leftJoin('users as c2', 'delivery.id_driver', '=', 'c2.id') // было
                ->leftJoin('guideTransport as c2', 'delivery.id_driver', '=', 'c2.id')
                ->whereIn('delivery.status', ['1','2'])
                ->select('delivery.id', 'delivery.id as deliveryID', 'delivery.title', 'delivery.statusID',
                    'delivery.phoneContacts', 'delivery.distance', 'delivery.location', 'delivery.mercenary',
                    'delivery.BN', 'delivery.urgent', 'delivery.loadingAddress',
                    'delivery.comment', 'delivery.commentManager', 'delivery.start', 'delivery.end',
                    'delivery.status',
                    'statusID.name as statusName', 'statusID.color as color', 'delivery.user_original', 
                    'c1.abb as abbManager', DB::raw('CONCAT(c1.first_name, " ", c1.last_name) AS managerName'),
                    'c2.abb as abbDrive', DB::raw('CONCAT(c2.marka, " ", c2.nomer, " ", c2.mercenaryName) AS driverName'))
                    //'c2.abb as abbDrive', DB::raw('CONCAT(c2.first_name, " ", c2.last_name) AS driverName')) //было
                ->get();
        */

        //dd($request->managerSelect == 'undefined');

        $query = Delivery::query();

        if ($request->managerSelect != 'undefined') {
            if ($request->managerSelect) {
                $query->where('delivery.id_manager', $request->managerSelect);
            }
        }

        $data = $query->leftJoin('statusID', 'delivery.statusID', '=', 'statusID.id')
                ->leftJoin('users as c1', 'delivery.id_manager', '=', 'c1.id')
                ->leftJoin('guideTransport as c2', 'delivery.id_driver', '=', 'c2.id')
                ->whereIn('delivery.status', ['1','2'])
                ->select('delivery.id', 'delivery.id as deliveryID', 'delivery.title', 'delivery.statusID',
                    'delivery.phoneContacts', 'delivery.distance', 'delivery.location', 'delivery.mercenary',
                    'delivery.BN', 'delivery.urgent', 'delivery.loadingAddress',
                    'delivery.comment', 'delivery.commentManager', 'delivery.start', 'delivery.end',
                    'delivery.status', 
                    'statusID.name as statusName', 'statusID.color as color', 'delivery.user_original', 
                    'c1.abb as abbManager', DB::raw('CONCAT(c1.first_name, " ", c1.last_name) AS managerName'),
                    DB::raw('CONCAT(c2.marka, " ", c2.nomer, " ", c2.mercenaryName) AS driverName'))
                ->get();

                return response()->json($data);

        //return view('calendarTransport');
    }

    public function action(Request $request)
    {
        event(new LoggedUserActivity);
        if ($request->ajax()) {
            $delivery = null;

            try {
                switch ($request->type) {
                    case 'add':

                        //сделать проверку заполненных полей или делать подсветк звездочкой обызательные поля
                        //request()->validate(Event::$rules);

                        $user_original = Auth::user()->id;
                        $user = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                        
                        if (isset($request->phoneContacts)) {

                            $phoneContacts = preg_replace('/^\+?(8|7)/', '', $request->phoneContacts);
                            $phoneContacts = preg_replace('/^\+?7|\|7|\D/', '', $phoneContacts);

                            $contacts = ContactsDelivery::updateOrCreate([
                                'phoneContacts' => $phoneContacts,
                            ],[
                                'nameContacts' => $request->nameContacts,
                                'partner' => '0',
                            ]);

                        } else {
                            $phoneContacts = null;
                        }


                        if (isset($request->phoneContacts2)) {

                            $phoneContacts2 = preg_replace('/^\+?(8|7)/', '', $request->phoneContacts2);
                            $phoneContacts2 = preg_replace('/^\+?7|\|7|\D/', '', $phoneContacts2);

                            $contacts = ContactsDelivery::updateOrCreate([
                                'phoneContacts' => $phoneContacts2,
                            ],[
                                'nameContacts' => $request->nameContacts2,
                                'partner' => '0',
                            ]);

                        } else {
                            $phoneContacts2 = null;
                        }

                        
                        $delivery = Delivery::create([
                            'title' => $request->title,
                            'sourceClient' => $request->sourceClient,
                            'urgent' => $request->urgent,
                            'BN' => $request->BN,
                            'mercenary' => $request->mercenary,
                            'nomer2' => $request->nomer2,
                            'phoneContacts' => $phoneContacts,
                            'phoneContacts2' => $request->phoneContacts2,
                            'loadingAddress' => $request->loadingAddress,
                            'location' => $request->location,
                            'distance' => $request->distance,
                            'tone' => $request->tone,
                            'price' => $request->price,
                            'amount' => $request->amount,
                            'comment' => $request->comment,
                            'commentManager' => $request->commentManager,
                            'start' => $request->start,
                            'end' => $request->end,
                            'user_original' => $user_original,
                            'user' => $user,
                            'statusID' => $request->statusID,
                            'id_manager' => $request->id_manager,
                            'id_driver' => $request->id_driver,
                        ]);

                        //dd($delivery);
                        $value_new = json_encode($delivery);
                        HistoryDelivery::insert(array(
                            'deal' => $delivery->id,
                            'status' => "1",
                            'type' => 'HistoryDelivery',
                            'user_original' => $user_original,
                            'value_old' => '',
                            'value_new' => $value_new
                        ));

                        break;

                    case 'update':
                        $user = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                        $user_original = Auth::user()->id;

                        //dd($request->start);
                        if (isset($request->phoneContacts)) {

                            $phoneContacts = preg_replace('/^\+?(8|7)/', '', $request->phoneContacts);
                            $phoneContacts = preg_replace('/^\+?7|\|7|\D/', '', $phoneContacts);

                            $contacts = ContactsDelivery::updateOrCreate([
                                'phoneContacts' => $phoneContacts,
                            ],[
                                'nameContacts' => $request->nameContacts,
                                'partner' => '0',
                            ]);

                        } else {
                            $phoneContacts = null;
                        }

                        if (isset($request->phoneContacts2)) {

                            $phoneContacts2 = preg_replace('/^\+?(8|7)/', '', $request->phoneContacts2);
                            $phoneContacts2 = preg_replace('/^\+?7|\|7|\D/', '', $phoneContacts2);

                            $contacts = ContactsDelivery::updateOrCreate([
                                'phoneContacts' => $phoneContacts2,
                            ],[
                                'nameContacts' => $request->nameContacts2,
                                'partner' => '0',
                            ]);

                        } else {
                            $phoneContacts2 = null;
                        }


                        //dd($request->nameContacts);
                        $delivery = Delivery::findOrFail($request->id);
                        $getOriginal = $delivery->getOriginal();
                        
                        $delivery->update([
                            'title' => $request->title,
                            'sourceClient' => $request->sourceClient,
                            'urgent' => $request->urgent,
                            'BN' => $request->BN,
                            'mercenary' => $request->mercenary,
                            'nomer2' => $request->nomer2,
                            'phoneContacts' => $phoneContacts,
                            'user' => $user,
                            'phoneContacts2' => $phoneContacts2,
                            'tone' => $request->tone,
                            'price' => $request->price,
                            'amount' => $request->amount,
                            'loadingAddress' => $request->loadingAddress,
                            'location' => $request->location,
                            'distance' => $request->distance,
                            'comment' => $request->comment,
                            'commentManager' => $request->commentManager,
                            'start' => $request->start,
                            'end' => $request->end,
                            'statusID' => $request->statusID,
                            'id_manager' => $request->id_manager,
                            'id_driver' => $request->id_driver,
                        ]);

                        $getChanges = $delivery->getChanges();
                        $original = [];
                        foreach ($delivery->getChanges() as $key => $value) {
                            $original[$key] = $getOriginal[$key];
                        }

                        $value_old = json_encode($original);
                        $value_new = json_encode($getChanges);

                        HistoryDelivery::insert(array(
                            'deal' => $request->id,
                            'status' => "2",
                            'type' => 'HistoryDelivery',
                            'user_original' => $user_original,
                            'value_old' => $value_old,
                            'value_new' => $value_new
                        ));

                        break;

                    case 'eventDrop':
                        $user = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                        $user_original = Auth::user()->id;

                        $delivery = Delivery::findOrFail($request->id);
                        $getOriginal = $delivery->getOriginal();

                        $delivery->update([
                            'user' => $user,
                            'start' => $request->start,
                            'end' => $request->end,
                        ]);
                        
                        $getChanges = $delivery->getChanges();
                        $original = [];
                        foreach ($delivery->getChanges() as $key => $value) {
                            $original[$key] = $getOriginal[$key];
                        }

                        $value_old = json_encode($original);
                        $value_new = json_encode($getChanges);

                        HistoryDelivery::insert(array(
                            'deal' => $request->id,
                            'status' => "4",
                            'type' => 'HistoryDelivery',
                            'user_original' => $user_original,
                            'value_old' => $value_old,
                            'value_new' => $value_new
                        ));

                        break;

                    case 'list':
                        $delivery = Delivery::leftJoin('contactsDelivery as c1', 'delivery.phoneContacts', '=', 'c1.phoneContacts')
                        ->leftJoin('contactsDelivery as c2', 'delivery.phoneContacts2', '=', 'c2.phoneContacts')
                        ->leftJoin('users', 'delivery.id_driver', '=', 'users.id')
                        ->leftJoin('statusID', 'delivery.statusID', '=', 'statusID.id')
                        ->select('delivery.*', 'c1.nameContacts', 'delivery.distance', 'c2.nameContacts as nameContacts2',
                            'statusID.name as statusName', 'statusID.color as color', 'users.address as addressHome')
                        ->where('delivery.id', $request->id)->first();
                        break;

                    case 'search':

                        $delivery = Delivery::where('phoneContacts', 'LIKE', "%{$request->search}%")
                        ->orWhere('id', 'LIKE', "%{$request->search}%")
                        ->orWhere('title', 'LIKE', "%{$request->search}%")
                        ->orderBy('start', 'desc')
                        ->get();
                        //$delivery = json_encode($delivery);
                        //$delivery = response()->json($delivery->original);

                        break;

                    
                    case 'phone':

                        $delivery = ContactsDelivery::where('nameContacts', 'LIKE', "%{$request->search}%") 
                        ->orWhere('phoneContacts', 'LIKE', "%{$request->search}%") 
                        ->get();
                        //$delivery = json_encode($delivery);
                        //$delivery = response()->json($delivery->original);

                        break;

                    case 'delete':
                        //Delivery::destroy($request->id);
                        $user_original = Auth::user()->id;

                        $delivery = Delivery::findOrFail($request->id);
                        $getOriginal = $delivery->getOriginal();

                        $delivery->update([
                            'status' => $request->status,
                            'commentDelete' => $request->commentDelete,
                        ]);
                        
                        $getChanges = $delivery->getChanges();
                        $original = [];
                        foreach ($delivery->getChanges() as $key => $value) {
                            $original[$key] = $getOriginal[$key];
                        }

                        $value_old = json_encode($original);
                        $value_new = json_encode($getChanges);

                        HistoryDelivery::insert(array(
                            'deal' => $request->id,
                            'status' => "3",
                            'type' => 'HistoryDelivery',
                            'user_original' => $user_original,
                            'value_old' => $value_old,
                            'value_new' => $value_new
                        ));

                        break;

                    case 'clone':
                        $user = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                        $id_user = Auth::user()->id;
                        $user_original = Auth::user()->id;
                        
                        $delivery = Delivery::findOrFail($request->id);

                        $diffStart = Carbon::parse($delivery->start);
                        $diffEnd = Carbon::now();

                        if ($diffStart < $diffEnd) {
                            $difference = $diffStart->diff(Carbon::parse($diffEnd))->days;
                            //dd($difference);

                            $start = Carbon::parse($delivery->start)->addDays($difference);
                            //$start = Carbon::now()->addDays(1);
                            $start = $start->format('Y-m-d H:i:s');

                            $end = Carbon::parse($delivery->end)->addDays($difference);
                            //$end = Carbon::now()->addDays(1);
                            $end = $end->format('Y-m-d H:i:s');
                            //dd($start);
                        } else {
                            $start = Carbon::parse($delivery->start)->format('Y-m-d H:i:s');
                            $end = Carbon::parse($delivery->end)->format('Y-m-d H:i:s');
                        }

                        $delivery = $delivery->replicate([
                            'created_at',
                            'updated_at'
                        ]);

                        $delivery = $delivery->replicate()->fill([
                            'user' => $user,
                            'user_original' => $id_user,
                            'start' => $start,
                            'end' => $end
                        ]);

                        $value_new = json_encode($delivery);

                        //dd($delivery);
                        $delivery->save();

                        HistoryDelivery::insert(array(
                            'deal' => $delivery->id,
                            'status' => "5",
                            'type' => 'HistoryDelivery',
                            'user_original' => $user_original,
                            'value_old' => '',
                            'value_new' => $value_new
                        ));
    
                        break;

                    case 'history':
                        //dd();
                        $delivery = HistoryDelivery::leftJoin('users', 'historyDelivery.user_original', '=', 'users.id')
                        ->leftJoin('delivery', 'historyDelivery.deal', '=', 'delivery.id')
                        ->leftJoin('statusID', 'delivery.statusID', '=', 'statusID.id')
                        ->where('deal', $request->id)
                        ->select('historyDelivery.*', 'delivery.start', 'delivery.end', 'delivery.location', 
                            'statusID.name as statusName', 'delivery.title',
                            DB::raw('CONCAT(users.first_name, " ", users.last_name) AS user_name'))
                            ->orderBy('historyDelivery.id', 'desc')
                        ->get();

                        //переделать для дат
                        //$event->start = Carbon::createFromFormat('Y-m-d H:i:s', $event->start)->format('Y-m-d');

                        $html = "";
                        $value_old = "";
                        $value_new = "";
                        if(!empty($delivery)){
                            //dd($delivery->id);
                            foreach ($delivery as $deliverys) {
                                //dd($deliverys->id);
                                if(!empty($deliverys->value_old)){
                                    foreach($deliverys->value_old as $key => $value) {
                                        if ($key == 'updated_at') {
                                            $updated_at = Carbon::parse($value)->format('d.m.Y H:i:s');
                                        
                                            $value_old = $value_old . 
                                            "<p class=''>" . $key . ": " . $updated_at . "</p>";
                                        } else {
                                            $value_old = $value_old . 
                                            "<p class=''>" . $key . ": " . $value . "</p>";
                                        }
                                    }
                                }

                                foreach($deliverys->value_new as $key => $value) {
                                    if ($key == 'updated_at') {
                                        $updated_at = Carbon::parse($value)->format('d.m.Y H:i:s');
                                    
                                        $value_new = $value_new . 
                                        "<p class=''>" . $key . ": " . $updated_at . "</p>";
                                    } else {
                                        $value_new = $value_new . 
                                        "<p class=''>" . $key . ": " . $value . "</p>";
                                    }
                                    
                                    
                                }
                                
                                $created_at = Carbon::parse($deliverys->created_at)->format('d.m.Y H:i:s');
                                $html = $html . 
                                    "<div class='form-row'><div class='form-group col-md-12'>" .
                                    "<small class='date' style='float: right;'>" . $created_at . " " . $deliverys->user_name . "</small>" .
                                    "</div>" .
                                    
                                    "<div class='form-group col-md-6'><strong>Старые значения</strong>" .
                                    $value_old .

                                    "</div><div class='form-group col-md-6'><strong>Новые значения</strong>" .
                                    $value_new .

                                    "</div></div><hr class='hr-line'>";

                                $value_old = "";
                                $value_new = "";
                            }
                        }
                        $response['html'] = $html;
                  
                        return response()->json($response);





                        //dd($delivery);
    
                        break;

                    default:
                        // Gérez d'autres types d'actions si nécessaire
                        break;
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            //dd(response()->json($event));
            return response()->json($delivery);
        }
    }



    public function select(Request $request)
    {
        event(new LoggedUserActivity);
        if ($request->ajax()) {
            $select = null;

            try {
                switch ($request->type) {

                    case 'statusID':
                        $select = StatusID::all();

                        //$select = StatusID::leftJoin('statusID', 'delivery.statusID', '=', 'statusID.id')
                        //->select('delivery.*', 'statusID.name as statusName', 'statusID.color as color')
                        //->get();

                        break;
                    
                    case 'id_manager':

                        $select = User::whereIn('role_id', ['1', '3', '4'])
                            ->where('status', 'Active')
                            ->select('id',
                            DB::raw('CONCAT(users.first_name, " ", users.last_name) AS name'))
                        ->get();

                        break;

                    case 'id_driver':

                        //сделать выводит из настроек, по условию машины или водители из пользователей
                        /* GuideTransport
                        $select = User::where('role_id', '5')
                            ->where('status', 'Active')
                            ->select('id',
                            DB::raw('CONCAT(users.first_name, " ", users.last_name) AS name'))
                        ->get();
                        */

                        $select = GuideTransport::select('id',
                            DB::raw('CONCAT(marka, " ", nomer, " ", mercenaryName) AS name'))
                        ->get();
    
                        break;

                    case 'id_driver_adress':

                        $select = GuideTransport::where('id', $request->id_driver)
                            ->select('address')
                        ->get();
    
                        break;

                    case 'checkDate':

                        $query = Delivery::query();

                        if ($request->id) {
                            //dd($request->id);
                            $query->where('delivery.id', 'NOT LIKE', $request->id);
                        }

                        /*
                        $select = $query->leftJoin('users as c1', 'delivery.id_manager', '=', 'c1.id')
                            ->leftJoin('guideTransport as c2', 'delivery.id_driver', '=', 'c2.id')
                            //->whereDate('delivery.status', ['1','2'])
                            ->where('delivery.id_driver', $request->id_driver)
                            ->whereDate('delivery.start', '=', Carbon::parse($request->start)->format('Y-m-d'))
                            ->where([['delivery.end', '<=', $request->end], ['delivery.start', '>=', $request->start]]) 
                            //->whereDate('delivery.start', '>=', $request->start)
                            //->whereDate('delivery.end', '<=', $request->end)
                            //->where('id_driver', 'id_driver')
                            ->select('delivery.id', 'delivery.id as deliveryID', 'delivery.title', 'delivery.statusID',
                                'delivery.phoneContacts', 'delivery.distance', 'delivery.location', 'delivery.mercenary',
                                'delivery.BN', 'delivery.urgent', 'delivery.loadingAddress',
                                'delivery.comment', 'delivery.commentManager', 'delivery.start', 'delivery.end',
                                'delivery.status', 'delivery.user_original', 
                                DB::raw('CONCAT(c1.first_name, " ", c1.last_name) AS managerName'),
                                DB::raw('CONCAT(c2.marka, " ", c2.nomer, " ", c2.mercenaryName) AS driverName'))
                            ->get();
                        */

                        /*
                            $select = $query->leftJoin('users as c1', 'delivery.id_manager', '=', 'c1.id')
                                ->leftJoin('guideTransport as c2', 'delivery.id_driver', '=', 'c2.id')
                                ->where('delivery.id_driver', $request->id_driver)
                                ->whereDate('delivery.start', '=', Carbon::parse($request->start)->format('Y-m-d'))
                                ->where(function ($query1) use ($request) {
                                    $query1->where([['delivery.end', '<=', $request->end], ['delivery.start', '>=', $request->start]])
                                        ->orWhere([['delivery.end', '>=', $request->end], ['delivery.start', '>=', $request->start]]);
                                    })->where(function ($query1) use ($request) {
                                    $query1->where([['delivery.end', '>=', $request->end], ['delivery.start', '>=', $request->start]]);
                                        //->orWhere('d', '=', 1);
                                })
                            ->select('delivery.id', 'delivery.id as deliveryID', 'delivery.title', 'delivery.statusID',
                                'delivery.phoneContacts', 'delivery.distance', 'delivery.location', 'delivery.mercenary',
                                'delivery.BN', 'delivery.urgent', 'delivery.loadingAddress',
                                'delivery.comment', 'delivery.commentManager', 'delivery.start', 'delivery.end',
                                'delivery.status', 'delivery.user_original', 
                                DB::raw('CONCAT(c1.first_name, " ", c1.last_name) AS managerName'),
                                DB::raw('CONCAT(c2.marka, " ", c2.nomer, " ", c2.mercenaryName) AS driverName'))
                            ->get();
                        */

                        $startTime = $request->start; 
                        $endTime = $request->end; 

                        $select = $query->leftJoin('users as c1', 'delivery.id_manager', '=', 'c1.id')
                        ->leftJoin('guideTransport as c2', 'delivery.id_driver', '=', 'c2.id')
                        ->where('delivery.id_driver', $request->id_driver)
                        ->where('c2.mercenary', 0)
                        ->whereIn('delivery.status', ['1'])
                        ->whereDate('delivery.start', '=', Carbon::parse($request->start)->format('Y-m-d'))
                        ->where(function ($query1) use ($startTime, $endTime) { 
                            $query1
                            ->where(function ($query1) use ($startTime, $endTime) {
                                $query1
                                    ->where('start', '<=', $startTime)
                                    ->where('end', '>', $startTime);
                            })
                            ->orWhere(function ($query1) use ($startTime, $endTime) {
                                $query1
                                    ->where('start', '<', $endTime)
                                    ->where('end', '>=', $endTime);
                            });
                        })->select('delivery.id', 'delivery.id as deliveryID', 'delivery.title', 'delivery.statusID',
                            'delivery.phoneContacts', 'delivery.distance', 'delivery.location', 'delivery.mercenary',
                            'delivery.BN', 'delivery.urgent', 'delivery.loadingAddress',
                            'delivery.comment', 'delivery.commentManager', 'delivery.start', 'delivery.end',
                            'delivery.status', 'delivery.user_original', 
                            DB::raw('CONCAT(c1.first_name, " ", c1.last_name) AS managerName'),
                            DB::raw('CONCAT(c2.marka, " ", c2.nomer, " ", c2.mercenaryName) AS driverName'))
                        ->get();

                            //dd($select->count());
    
                            if ($select->count()) {

                            }

                            break;

                    default:
                    
                        break;
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return response()->json($select);
        }
    }



    public function calculator(Request $request)
    {
        event(new LoggedUserActivity);
        if ($request->ajax()) {
            $calculator = null;

            try {
                switch ($request->type) {

                    case 'tone':

                        //dd($request->tone);

                        if ($request->tone <= 400) {
                            $exit = 1800;
                            $priceKm = 13;
                            $distanceAll = $request->distanceAll;

                            $calculator = $exit + ($distanceAll * $priceKm);

                        } else if ($request->tone <= 1500) {
                            $exit = 2200;
                            $priceKm = 13;
                            $distanceAll = $request->distanceAll;

                            $calculator = $exit + ($distanceAll * $priceKm);

                        } else if ($request->tone <= 3000) {
                            $exit = 3500;
                            $priceKm = 13;
                            $distanceAll = $request->distanceAll;

                            $calculator = $exit + ($distanceAll * $priceKm);

                        } else if ($request->tone <= 4000) {
                            $exit = 4000;
                            $priceKm = 14;
                            $distanceAll = $request->distanceAll;

                            $calculator = $exit + ($distanceAll * $priceKm);

                        } else if ($request->tone <= 5000) {
                            $exit = 4000;
                            $priceKm = 16;
                            $distanceAll = $request->distanceAll;
                            //dd($distanceAll);
                            $calculator = $exit + ($distanceAll * $priceKm);

                         } else if ($request->tone <= 6000) {
                            $exit = 4000;
                            $priceKm = 18;
                            $distanceAll = $request->distanceAll;
                            //dd($distanceAll);
                            $calculator = $exit + ($distanceAll * $priceKm);

                        }
                        //dd($calculator);

                        break;

                    default:
                    
                        break;
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            
            return response()->json($calculator);
        }
    }



}