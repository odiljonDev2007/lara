<?php

namespace Vanguard\Http\Controllers\Api;

use Vanguard\Http\Controllers\Controller;

use DB;
use Vanguard\Contacts;
use Vanguard\Call;
use Vanguard\HistoryObjectCall;
use Carbon\Carbon;
use Vanguard\Repositories\PbxApi\PbxApiRepository;
use Illuminate\Support\Facades\Storage;
use Mail;

use Illuminate\Http\Request;

class PbxController extends ApiController
{
    //private $pbx;

    public function __construct(PbxApiRepository $pbx) {
        //$this->middleware('auth');
        //$this->middleware('permission:calendar_brigadir');
        $this->pbx = $pbx;
    }

    private	function onpbx_get_secret_key(){
		$curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api2.onlinepbx.ru/pbx17187.onpbx.ru/auth.json',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => 'auth_key=YTZPRzN2d254VkxhT2kwQU1qS08xOW5tbVBDMVBPWGk',
          CURLOPT_HTTPHEADER => array(
            'domain: pbx17187.onpbx.ru',
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));
        
        //$response = curl_exec($curl);
        
        $response = json_decode(curl_exec($curl), true);
        
        curl_close($curl);
        
        //print_r($response);
		
		if ($response){
		    return $response;
		}else {
		    return false;
		}
	}

    private function UR_exists($url){
        $headers=get_headers($url);
        return stripos($headers[0],"200 OK")?true:false;
    }
    
    public function onlinepbxInbound(Request $request)
    {
        
        $data = $request->all();

        $calendarId = "";
        
        //echo($_POST["caller_number"]); номер звонящего
        //echo($_POST["trunk"]); номер на который идет звонок

        $phone_contacts = $data["caller_number"];
        
        $gateway = $data['trunk'];
        
        if ($gateway == "79254772667") { // юла
            $avito = "2";
        } else if ($gateway == "79254770859") { //авито
            $avito = "1";
        } else if ($gateway == "78005056560") { //мех.штукатурка
            $avito = "3";
        } else if ($gateway == "74951452300") { //мех.штукатурка
            $avito = "3";
        } else if ($gateway == "84951650540") { //мех.штукатурка
            $avito = "3";
        } else if ($gateway == "74951453010") { //баупутц
            $avito = "4";
        } else if ($gateway == "74956687115") { //маш.штукатурка
            $avito = "5";
        } else if ($gateway == "79254771052") { //авито вагонка
            $avito = "6";
        }
        
        $phone_contacts = preg_replace('/^\+?(8|7)/', '', $phone_contacts);

        if (strlen($phone_contacts) > "9") {

            $result = Contacts::where('phone_contacts', $phone_contacts)
                ->get();

            if ($result->count()){
                
                foreach ($result as $key => $value) {
                    $name_contacts = $result[$key]['name_contacts'];
                    $unique_call = "0";
                }
                                    
            } else {

                // интересно будет добавить откуда звонящий (источник)
                $name_contacts = "Входящий ".$phone_contacts;

                Contacts::insert(array(
                    'id' => NULL,
                    'phone_contacts' => $phone_contacts,
                    'name_contacts' => $name_contacts,
                    'avito' => $avito,
                    //'bonus' => $bonus, // ввести когда будут бонусы начисляться
                ));

                $unique_call = "1";
                $calendarId = "14";
            
            }

        }

        $uuid = $data["uuid"];
        $accountcode = $data['type'];
        
        if ($calendarId !== "14") {
            $calendarId = "13";
        }
        
        $start = date('Y-m-d H:i:s');
        $end = date('Y-m-d H:i:s');
    
        $start_stamp = strtotime($start);
    
        $title_time = date('H:i:s');
        $info_data = date('d.m.Y H:i:s');


        Call::Create(array(
            'uuid' => $uuid,
            'phone_contacts' => $phone_contacts,
            'unique_call' => $unique_call,
            'start' => $start,
            'end' => $end,
            'calendarId' => $calendarId,
            'accountcode' => $accountcode,
            'title_time' => $title_time,
            'gateway' => $gateway,
            'info_data' => $info_data,
            'start_stamp' => $start_stamp
        ));


        $status_leads = 'Новый входящий';
        $status = "1";
        $type = "Входящий";
        $field_leads = $unique_call;
        $users = $phone_contacts.' '.$name_contacts;
        $value_old = json_encode("{Входящий звонок ".$phone_contacts."}");

        HistoryObjectCall::insert(array(
            'status' => "1",
            'type' => 'HistoryCallPbx',
            'value_old' => $value_old
        ));
        
    }

        
    public function onlinepbxOutbound(Request $request)
    {
        
        
        $max = Call::where('accountcode', 'outbound')
            ->max('id');

        $result = Call::where('id', $max)
            ->get();

        foreach ($result as $key => $value) {
            $start_stamp = $result[$key]['start_stamp'] + 1;
        }

        $data_key_array = $this->onpbx_get_secret_key();

        $secret_key = $data_key_array['data']['key'];
        $key_id = $data_key_array['data']['key_id'];

        $keys = $key_id.':'.$secret_key;

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api2.onlinepbx.ru/pbx17187.onpbx.ru/mongo_history/search.json',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => 'start_stamp_from='.$start_stamp.'&accountcode=outbound',
          CURLOPT_HTTPHEADER => array(
            'domain: pbx17187.onpbx.ru',
            'x-pbx-authentication: '.$keys,
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));

        $data_array = json_decode(curl_exec($curl), true);

        curl_close($curl);

        $data = $data_array['data'];

        if (sizeof($data)) {

            foreach($data as $key=>$arr){

                $uuid = $arr['uuid'];
                $destination_number = $arr['caller_id_number'];
                $phone_contacts = $arr['destination_number'];
                $phone_contacts = preg_replace('/^\+?(8|7)/', '', $phone_contacts);
                
                $date_start = date('Y-m-d H:i:s', $arr['start_stamp']);
                $start = date('Y-m-d H:i:s', strtotime($date_start));
        
                $date_end = date('Y-m-d H:i:s', $arr['end_stamp']);
                $end = date('Y-m-d H:i:s', strtotime($date_end));
                $time_call = $arr['duration'];
                $user_talk_time = $arr['user_talk_time'];
        
                if ($arr['user_talk_time'] == "0") {
                    $calendarId = "12";
                } else {
                    $calendarId = "11";
                }
                
                $hangup_cause = $arr['hangup_cause'];
                $accountcode = $arr['accountcode'];
                $gateway = $arr['gateway'];
                
                $start_stamp = $arr['start_stamp'];
                $end_stamp = $arr['end_stamp'];
                
                $title_time = date('H:i:s', strtotime($date_start));
                $info_data = date('d.m.Y H:i:s', strtotime($date_start));
                
                

                if (strlen($phone_contacts) > "9") {

                    $result = Contacts::where('phone_contacts', $phone_contacts)
                        ->get();
        
                    if ($result->count()){
                        
                        foreach ($result as $key => $value) {
                            $name_contacts = $result[$key]['name_contacts'];
                            $unique_call = "0";
                        }
                                            
                    } else {
        
                        // интересно будет добавить откуда звонящий (источник)
                        $name_contacts = "Исходящий ".$phone_contacts;
        
                        Contacts::insert(array(
                            'id' => NULL,
                            'phone_contacts' => $phone_contacts,
                            'name_contacts' => $name_contacts,
                        ));
        
                        $unique_call = "1";
                    
                    }
        
                }

                Call::Create(array(
                    'uuid' => $uuid,
                    'phone_contacts' => $phone_contacts,
                    'unique_call' => $unique_call,
                    'start' => $start,
                    'end' => $end,
                    'destination_number' => $destination_number,
                    'time_call' => $time_call,
                    'user_talk_time' => $user_talk_time,
                    'hangup_cause' => $hangup_cause,
                    'calendarId' => $calendarId,
                    'accountcode' => $accountcode,
                    'start_stamp' => $start_stamp,
                    'end_stamp' => $end_stamp,
                    'gateway' => $gateway,
                    'title_time' => $title_time,
                    'info_data' => $info_data
                ));

                $value_old = json_encode("{Исходящий звонок ".$phone_contacts."}");

                HistoryObjectCall::insert(array(
                    'status' => "1",
                    'type' => 'HistoryCallPbx',
                    'value_old' => $value_old
                ));

                
                    
                
            }


        }

        
        
    }
    
        
    public function onlinepbxDownload(Request $request)
    {
        
        $data_key_array = $this->onpbx_get_secret_key();
        
        $secret_key = $data_key_array['data']['key'];
        $key_id = $data_key_array['data']['key_id'];
        
        $keys = $key_id.':'.$secret_key;

        $start = date("Y-m-d");

        $result = Call::where('start', '>=', $start)
            ->where('end', '<=', $start)
            ->orderBy('start_stamp', 'desc')
            ->get();

        foreach ($result as $key => $value) {
            $start_stamp = $result[$key]['start_stamp'] + 1;

            $id = $result[$key]['id'];
            $phone_contacts = $result[$key]['phone_contacts'];
            $uuid = $result[$key]['uuid'];

            if ($result[$key]['download']) {
                if(@fopen($result[$key]['download'], "r")) {
                    echo "существует </br>";
            	} else {
                    echo "не существует </br>";
            		$result[$key]['download'] = "";
            	}
            }

            if ($result[$key]['download'] == "" & $result[$key]['time_call'] != "0") {
                
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api2.onlinepbx.ru/pbx17187.onpbx.ru/mongo_history/search.json',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => 'uuid='.$value['uuid'].'&download=1',
                    CURLOPT_HTTPHEADER => array(
                    'domain: pbx17187.onpbx.ru',
                    'x-pbx-authentication: '.$keys,
                    'Content-Type: application/x-www-form-urlencoded'
                    ),
                ));
            
                $data_array = json_decode(curl_exec($curl), true);
                
                curl_close($curl);

                $link = $data_array['data'];

                if ($link) {

                    $time_mp3 = time();

                    $contents = file_get_contents($link);
                    $name = basename($id."_".$time_mp3.".mp3");
                    Storage::disk('call')->put($name, $contents);
                    $url = Storage::url($name);
                    
                    $download = Storage::disk('call')->url($name);

                    $call = Call::findOrFail($id);
                        
                    $call->update([
                        'download' => $download,
                    ]);

                }
            }
        }
    }
        
    public function onlinepbxNotifications(Request $request)
    {
        
        $data_key_array = $this->onpbx_get_secret_key();
        
        $secret_key = $data_key_array['data']['key'];
        $key_id = $data_key_array['data']['key_id'];
        
        $keys = $key_id.':'.$secret_key;

        $start = date("Y-m-d");

        $result = Call::where('start', '>=', $start)
            ->where('end', '<=', $start)
            ->orderBy('start_stamp', 'desc')
            ->get();

        foreach ($result as $key => $value) {

            $id = $result[$key]['id'];
            $phone_contacts = $result[$key]['phone_contacts'];
            $uuid = $result[$key]['uuid'];

            if ($result[$key]['time_call'] == "0") {

                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api2.onlinepbx.ru/pbx17187.onpbx.ru/mongo_history/search.json',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => 'uuid='.$value['uuid'],
                    CURLOPT_HTTPHEADER => array(
                        'domain: pbx17187.onpbx.ru',
                        'x-pbx-authentication: '.$keys,
                        'Content-Type: application/x-www-form-urlencoded'
                    ),
                ));

                $data_array = json_decode(curl_exec($curl), true);
                curl_close($curl);
                $data = $data_array['data'];

                if (sizeof($data)) {

                    foreach($data as $key=>$arr){
                    
                        $destination_number = $arr['destination_number'];
                        $time_call = $arr['duration'];
                        $user_talk_time = $arr['user_talk_time'];
                            
                        $curl = curl_init();
                            
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api2.onlinepbx.ru/pbx17187.onpbx.ru/mongo_history/search.json',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => 'uuid='.$arr['uuid'],
                            CURLOPT_HTTPHEADER => array(
                                'domain: pbx17187.onpbx.ru',
                                'x-pbx-authentication: '.$keys,
                                'Content-Type: application/x-www-form-urlencoded'
                            ),
                        ));
                        
                        $response = curl_exec($curl);
                        
                        $datas_array = json_decode(curl_exec($curl), true);

                        curl_close($curl);

                        $hangup_cause = $arr['hangup_cause'];
                            
                        $start_stamp = $arr['start_stamp'];
                        $end_stamp = $arr['end_stamp'];
                            
                        if ($arr['user_talk_time'] == "0") {
                            $calendarId = "12";
                        } else {
                            $calendarId = "11";
                        }
                    
                        if ($arr['duration'] == "0") {
                            $calendarId = "13";
                        }

                        
                        if (strlen($phone_contacts) > "9") {

                            $result = Contacts::where('phone_contacts', $phone_contacts)
                                ->get();
                
                            if ($result->count()){
                                
                                foreach ($result as $key => $value) {
                                    $name_contacts = $result[$key]['name_contacts'];
                                    $unique_call = "0";
                                }
                                                    
                            } else {
                
                                // интересно будет добавить откуда звонящий (источник)
                                $name_contacts = "Исходящий ".$phone_contacts;
                
                                Contacts::insert(array(
                                    'id' => NULL,
                                    'phone_contacts' => $phone_contacts,
                                    'name_contacts' => $name_contacts,
                                ));
                
                                $unique_call = "1";
                            
                            }
                
                        }

                        $call = Call::findOrFail($id);
                        
                        $call->update([
                            'destination_number' => $destination_number,
                            'time_call' => $time_call,
                            'user_talk_time' => $user_talk_time,
                            'calendarId' => $calendarId,
                            'hangup_cause' => $hangup_cause,
                            'start_stamp' => $start_stamp,
                            'end_stamp' => $end_stamp,
                        ]);
                    }
                }

            }


        }

        // все вызовы
        $all_calls = Call::where('start', $start)
            ->count();
        
        // входящие вызовы
        $inbound = Call::where('start', $start)
            ->where('accountcode', 'inbound')
            ->count();
        
        // не отвечанные входящие вызовы
        $inbound_not = Call::where('start', $start)
            ->where('accountcode', 'inbound')
            ->where('calendarId', '12')
            ->count();

        // уникальные входящие вызовы 
        $unique = Call::where('start', $start)
            ->where('accountcode', 'inbound')
            ->where('unique_call', '1')
            ->count();
        
        // не отвечанные уникальные входящие вызовы 
        $unique_not = Call::where('start', $start)
            ->where('accountcode', 'inbound')
            ->where('unique_call', '1')
            ->where('calendarId', '12')
            ->count();
        
        // исходящие вызовы
        $outbound = Call::where('start', $start)
            ->where('accountcode', 'outbound')
            ->count();
        
        // не отвечанные исходящие вызовы
        $outbound_not = Call::where('start', $start)
            ->where('accountcode', 'outbound')
            ->where('calendarId', '12')
            ->count();

        if ($inbound_not) {
            $percent_inbound_not = ($inbound_not * 1) / $inbound;
            $percent_inbound_not = round($percent_inbound_not, 2);
            $percent_inbound_not = $percent_inbound_not * 100;    
        }        
            
        if ($unique) {
            $percent_unique_not = ($unique_not * 1) / $unique;
            $percent_unique_not = round($percent_unique_not, 2);
            $percent_unique_not = $percent_unique_not * 100;
        }




        // сделать рассылку на почту на ларавере
        $result = Call::leftJoin('contacts as c1', 'call.phone_contacts', '=', 'c1.phone_contacts')
            ->leftJoin('contacts as c2', 'call.phone_contacts2', '=', 'c2.phone_contacts')
            ->select('call.*', 'c1.name_contacts as nameContacts', 'c2.name_contacts as nameContacts2',
                    'call.status_calendarId as statusID', 'call.type as statusObject',
                    'call.phone_contacts as phoneContacts', 'call.phone_contacts2 as phoneContacts2')
            ->where('call.calendarId', '12')
            ->where('call.accountcode', 'inbound')
            ->where('call.notifications', '0')
            ->orderBy('call.start_stamp', 'desc')
            ->get();

        //dd($result);
        //dd(settings('app_name'));

        foreach ($result as $key => $value) {

            $id = $result[$key]['id'];
            $name_contacts = $result[$key]['name_contacts'];
            $phone_contacts = $result[$key]['phone_contacts'];
            $partner = $result[$key]['partner'];
            $unique_call = $result[$key]['unique_call'];
            $title_time = $result[$key]['title_time'];
            $info_data = $result[$key]['info_data'];
            $destination_number = $result[$key]['destination_number'];
            $time_call = $result[$key]['time_call'];
            $user_talk_time = $result[$key]['user_talk_time'];
            $download = $result[$key]['download'];
            $calendarId = $result[$key]['calendarId'];
            
            if ($unique_call == "1") {
                $value_title = 'Пропущенный УНИКАЛЬНЫЙ 8'.$phone_contacts.' - '.$title_time;
            } else {
                $value_title = 'Пропущенный 8'.$phone_contacts.' - '.$title_time;
            }
            
            $emails = ['lehaer@bk.ru', 'lehaer1@gmail.com'];
            
            Mail::send('mail.notifications.call', [
                'name_contacts' => $name_contacts,
                'phone_contacts' => $phone_contacts,
                'unique_call' => $unique_call,
                'id' => $id,
                'APP_URL' => env('APP_URL'),
                'APP_NAME' => env('MAIL_FROM_NAME'),
                'year' => Carbon::parse(now())->format('Y'),
                'date' => Carbon::parse(now())->format('d.m.Y H:i:s'),
                'value_title' => $value_title,
                'title_time' => $title_time,
                'time_call' => $time_call,
                'inbound' => $inbound,
                'inbound_not' => $inbound_not,
                'unique' => $unique,
                'unique_not' => $unique_not,
                'percent_inbound_not' => $percent_inbound_not,
                'percent_unique_not' => $percent_unique_not],
                function ($message) use ($emails) {
                    $message->from('info@mehanizirovannaya-shtukaturka.ru', env('MAIL_FROM_NAME'));
                    $message->to($emails)->subject('Система звонков');
            });
            
            $call = Call::findOrFail($id);
                        
            $call->update([
                'notifications' => '1',
            ]);
            


        }









    }




    
}
