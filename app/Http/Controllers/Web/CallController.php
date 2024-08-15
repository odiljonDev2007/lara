<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;

use Vanguard\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Auth;
use Vanguard\User;
use Vanguard\Call;
use Vanguard\Contacts;
use Vanguard\StatusID;
use Vanguard\StatusCall;
use Vanguard\StatusObject;
use Vanguard\HistoryObjectCall;
use Vanguard\GuideTransport;
use Vanguard\Events\User\LoggedUserActivity;

class CallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // сворачивание всех звонков
    public function array_unique_key($array, $key, $key2) { 
    	$tmp = $key_array = array(); 
    	$i = 0; 
        
    	foreach($array as $val) {
    		if (!in_array($val[$key], $key_array) || $val[$key2] == "1") { 
    			$key_array[$i] = $val[$key]; 
    			$tmp[] = $val; 
    		} 
    		$i++; 
    	} 
    	return $tmp; 
    }

    // поиск последнего дозвона не отвечанного
    public function array_unique_key2($array, $phoneContacts, $uniqueCall, $statusCall, $accountcode) { 
    	$tmp = $key_array = $tmp2 = array(); 
    	$i = 0;

    	foreach($array as $val) {
            if (!in_array($val[$phoneContacts], $key_array)) { 
                $key_array[$i] = $val[$phoneContacts]; 
                $tmp[] = $val; 
        	}
    		$i++; 
    	}
    	foreach($tmp as $val) {
    	    if ($val[$statusCall] == '12') {
                    $val['start'] = Carbon::parse(now())->format('Y-m-d');
                    $val['end'] = Carbon::parse(now())->format('Y-m-d');
                    $tmp2[] = $val; 
    	    }
    	    
    	    $i++;
    	}
    	return $tmp2; 
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
            'call.index',
            compact('id_user', 'admin')
        );
    }

    public function calendarAjax(Request $request)
    {   
        //event(new LoggedUserActivity);


        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        //dd($start);

        $query = Call::query();

        if ($request->callSelect != 'undefined') {
            if ($request->callSelect == 1) {
                $query->where('call.unique_call', $request->callSelect);
            } else if ($request->callSelect == 2) {
                //$query->where('call.unique_call', $request->callSelect);
                // -14 дней от текущей даты
                $date = strtotime('-4 days');
                $start = date('Y-m-d', $date);

            }
        }

        $data = $query->leftJoin('statusID', 'call.status_calendarId', '=', 'statusID.id')
                ->leftJoin('statusCall', 'call.calendarId', '=', 'statusCall.id')
                ->leftJoin('contacts as c1', 'call.phone_contacts', '=', 'c1.phone_contacts')
                ->leftJoin('sourceClient', 'c1.avito', '=', 'sourceClient.id')
                //->whereIn('call.status', ['1','2'])
                ->where('call.start', '>=', $start)
                ->where('call.end', '<=', $end)
                ->select('call.id', 'call.id as callID', 'call.title_time', 'call.status_calendarId as statusID',
                    'call.phone_contacts as phoneContacts', 'call.location',
                    'call.BN', 'call.accountcode', 'call.destination_number', 'call.start_stamp',
                    'call.user_talk_time', 'call.time_call', 'call.m2', 'c1.name_contacts as nameContacts',
                    'c1.avito as sourceCall', 'sourceClient.color as sourceCallColor',
                    'sourceClient.name as sourceCallName',
                    'call.thickness', 'call.price', 'call.comment', 'call.unique_call as uniqueCall',
                    'call.comment', 'call.start', 'call.end', 'call.download',
                    'call.calendarId as statusCall', 'statusCall.name as statusNameCall', 'statusCall.color as color',
                    'statusID.name as statusName', 'call.user',)
                ->orderBy('call.id', 'desc')
                ->get();

        if ($request->callSelect  == 2) {
            $data = $this->array_unique_key2($data, 'phoneContacts', 'uniqueCall', 'statusCall', 'accountcode');

        } else {
            $data = $this->array_unique_key($data, 'phoneContacts', 'uniqueCall'); 
        }
        
        

        return ($data);
    }

    public function action(Request $request)
    {
        event(new LoggedUserActivity);
        if ($request->ajax()) {
            $call = null;

            try {
                switch ($request->type) {           
                    case 'update':
                        $user = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                        $user_original = Auth::user()->id;

                        //dd($request->start);
                        if (isset($request->phoneContacts)) {

                            $phoneContacts = preg_replace('/^\+?(8|7)/', '', $request->phoneContacts);
                            $phoneContacts = preg_replace('/^\+?7|\|7|\D/', '', $phoneContacts);
                            
                            $contacts = Contacts::updateOrCreate([
                                'phone_contacts' => $phoneContacts,
                            ],[
                                'name_contacts' => $request->nameContacts,
                                'partner' => '0',
                            ]);

                        } else {
                            $phoneContacts = null;
                        }

                        if (isset($request->phoneContacts2)) {

                            $phoneContacts2 = preg_replace('/^\+?(8|7)/', '', $request->phoneContacts2);
                            $phoneContacts2 = preg_replace('/^\+?7|\|7|\D/', '', $phoneContacts2);

                            $contacts = Contacts::updateOrCreate([
                                'phone_contacts' => $phoneContacts2,
                            ],[
                                'name_contacts' => $request->nameContacts2,
                                'partner' => '0',
                            ]);

                        } else {
                            $phoneContacts2 = null;
                        }


                        //dd($request->nameContacts);
                        $call = Call::findOrFail($request->id);
                        $getOriginal = $call->getOriginal();
                        
                        $call->update([
                            'sourceClient' => $request->sourceClient,
                            'urgent' => $request->urgent,
                            'BN' => $request->BN,
                            'nomer2' => $request->nomer2,
                            'phone_contacts' => $phoneContacts,
                            'user' => $user,
                            'phone_contacts2' => $phoneContacts2,
                            'price' => $request->price,
                            'location' => $request->location,
                            'km' => $request->km,
                            'comment' => $request->comment,
                            'commentManager' => $request->commentManager,
                            'start' => $request->start,
                            'end' => $request->end,
                            'status_calendarId' => $request->statusID,
                            'type' => $request->statusObject,
                            'combine' => $request->combine,
                            'zamer' => $request->zamer,
                            'setka' => $request->setka,
                            'increased' => $request->increased,
                            'deals' => $request->deals,
                            'send_SMS' => $request->send_SMS,
                            'floor' => $request->floor,
                            'entrance' => $request->entrance,
                            'm2' => $request->m2,
                            'thickness' => $request->thickness,
                            'm2_price' => $request->m2_price,
                            'price_styazhka' => $request->price_styazhka,
                        ]);

                        $getChanges = $call->getChanges();
                        $original = [];
                        foreach ($call->getChanges() as $key => $value) {
                            $original[$key] = $getOriginal[$key];
                        }

                        $value_old = json_encode($original);
                        $value_new = json_encode($getChanges);

                        HistoryObjectCall::insert(array(
                            'deal' => $request->id,
                            'status' => "2",
                            'type' => 'HistoryCall',
                            'user_original' => $user_original,
                            'value_old' => $value_old,
                            'value_new' => $value_new
                        ));

                        break;

                    case 'list':
                        $call = Call::leftJoin('contacts as c1', 'call.phone_contacts', '=', 'c1.phone_contacts')
                        ->leftJoin('contacts as c2', 'call.phone_contacts2', '=', 'c2.phone_contacts')
                        ->leftJoin('statusID', 'call.status_calendarId', '=', 'statusID.id')
                        ->select('call.*', 'c1.name_contacts as nameContacts', 'c2.name_contacts as nameContacts2',
                            'call.status_calendarId as statusID', 'call.type as statusObject',
                            'call.phone_contacts as phoneContacts', 'call.phone_contacts2 as phoneContacts2', 
                            'statusID.name as statusName', 'statusID.color as color')
                        ->where('call.id', $request->id)->first();
                        break;

                    case 'search':

                        $call = Call::where('phone_contacts', 'LIKE', "%{$request->search}%")
                        ->orWhere('id', 'LIKE', "%{$request->search}%")
                        ->orWhere('title', 'LIKE', "%{$request->search}%")
                        ->orderBy('start', 'desc')
                        ->get();

                        break;

                    
                    case 'phone':

                        $call = Contacts::where('name_contacts', 'LIKE', "%{$request->search}%") 
                        ->orWhere('phone_contacts', 'LIKE', "%{$request->search}%") 
                        ->get();

                        break;

                    case 'history':
                        //dd();
                        $delivery = HistoryObjectCall::leftJoin('users', 'historyObjectCall.user_original', '=', 'users.id')
                        ->leftJoin('delivery', 'historyObjectCall.deal', '=', 'delivery.id')
                        ->leftJoin('statusID', 'delivery.statusID', '=', 'statusID.id')
                        ->where('deal', $request->id)
                        ->select('historyObjectCall.*', 'delivery.start', 'delivery.end', 'delivery.location', 
                            'statusID.name as statusName',
                            DB::raw('CONCAT(users.first_name, " ", users.last_name) AS user_name'))
                            ->orderBy('historyObjectCall.id', 'desc')
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
            return response()->json($call);
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

                    case 'statusObject':
                        $select = StatusObject::all();

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

                    default:
                    
                        break;
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return response()->json($select);
        }
    }



}