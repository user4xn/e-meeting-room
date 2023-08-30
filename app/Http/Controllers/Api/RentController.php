<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\MasterRoom;
use App\Models\Rent;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use DB;
use Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class RentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $fetch = Rent::when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })->get()->toArray();
            $folderPath = public_path('qrcodes');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            $reform = array_map(function ($new) {
                $data = [
                    'rent_id' => $new['id'], 
                    'room_id' => $new['room_id']
                ];
                $dataString = json_encode($data);
                $name_file = str_replace(' ','_', $new['event_name']);
                $folderPath = public_path('qrcodes');
                $qrcode = QrCode::size(300)->generate($dataString);
                $svgPath = $folderPath . '/qrcode_' . $name_file . '.svg';
                file_put_contents($svgPath, $qrcode);
                return [
                    'id' => $new['id'],
                    'user_id' => $new['user_id'],
                    'room_id' => $new['room_id'],
                    'date_start' => $new['date_start'],
                    'date_end' => $new['date_end'],
                    'time_start' => $new['time_start'],
                    'time_end' => $new['time_end'],
                    'event_name' => $new['event_name'],
                    'event_desc' => $new['event_desc'],
                    'guest_count' => $new['guest_count'],
                    'organization' => $new['organization'],
                    'status' => $new['status'],
                    'notes' => $new['notes'],
                    'qrcode' => asset('qrcodes/qrcode_'.$name_file.'.svg'),
                    'created_at' => $new['created_at'],
                    'updated_at' => $new['updated_at'],
                ];
            }, $fetch);
        
            return ResponseJson::response('success', 'Success Get List Rent.', 200, $reform);
        } catch (\Exception $e) {
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
        
    }

    public function listCalendar()
    {
        try{
            $fetch = Rent::whereIn('status', ['approved', 'unapproved'])
                ->get()
                ->toArray();

            $i = 0;
            $reform = array_map(function($new) use (&$i) { 
                $startTime = new DateTime($new['date_start'].' '.$new['time_start']);
                $endTime = new DateTime($new['date_end'].' '.$new['time_end']);

                $interval = $startTime->diff($endTime);
                $i++;
                return [
                    'id' => $new['id'],
                    'url' => '',
                    'title' => $new['event_name'],
                    'start' => $new['date_start'].' '.$new['time_start'],
                    'end' => $new['date_end'].' '.$new['time_end'],
                    'allDay' => ($interval->format('%h jam') >= 24) ? true : false,
                    'extendedProps' => array(
                        'calendar' => ($new['status'] == "approved") ? $new['organization'] : "Unapproved"
                    )
                ]; 
            }, $fetch);
            return ResponseJson::response('success', 'Success Get List Calendar.', 200, ['events' => $reform]); 
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function listPersonResponsible()
    {
        try{
            $users = User::select('users.id', 'username', 'email','name')
                ->leftjoin('user_details as ud', 'ud.user_id', '=', 'users.id')
                ->where('users.status', 'Active')
                ->where('ud.name', '!=', null)
                ->get();
            return ResponseJson::response('success', 'Success Get List Person Responsible.', 200, $users); 
            
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'room_id' => 'required|string',
            'date_start' => 'required',
            'date_end' => 'required',
            'time_start' => 'required',
            'time_end' => 'required',
            'event_name' => 'required',
            'event_desc' => 'required',
            'guest_count' => 'required',
            'organization' => 'required',
        ],[
            'user_id' => 'Please Input Request user_id.', 
            'room_id' => 'Please Input Request room_id.',
            'date_start' => 'Please Input Request date_start.',
            'date_end' => 'Please Input Request date_end.',
            'time_start' => 'Please Input Request time_start.',
            'time_end' => 'Please Input Request time_end.',
            'event_name' => 'Please Input Request event_name.',
            'event_desc' => 'Please Input Request event_desc.',
            'guest_count' => 'Please Input Request guest_count.',
            'organization' => 'Please Input Request organization.',
        ]);
        if ($validator->fails()) {
            return ResponseJson::response('failed', 'Error Validation', 422, ['error' => $validator->errors()]);
        }
        $check_user = User::select('id')
            ->where('id', $request->user_id)
            ->first();
        if(!$check_user){
            return ResponseJson::response('failed', 'Please Check Person Responsible', 400, null);
        }
        $check_room = MasterRoom::select('id')
            ->where('id', $request->room_id)
            ->first();
        if(!$check_room){
            return ResponseJson::response('failed', 'Please Check Master Room', 400, null);
        }
        DB::beginTransaction();
        try{
            $store_rent = new Rent();
            $store_rent->user_id = $request->user_id;
            $store_rent->room_id = $request->room_id;
            $store_rent->date_start = $request->date_start;
            $store_rent->date_end = $request->date_end;
            $store_rent->time_start = $request->time_start;
            $store_rent->time_end = $request->time_end;
            $store_rent->event_name = $request->event_name;
            $store_rent->event_desc = $request->event_desc;
            $store_rent->guest_count = $request->guest_count;
            $store_rent->organization = $request->organization;
            $store_rent->save();

            DB::commit();
            return ResponseJson::response('success', 'Success Store Data Rent.', 200, null); 
            
        }catch(\Exception $e){
            DB::rollback();
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function detail($id)
    {
        try{
            $rent = Rent::where('id', $id)
                ->with('Room')
                ->first();
            if(!$rent){
                return ResponseJson::response('failed', 'Rent Not Found.', 404, null); 
            }
            return ResponseJson::response('success', 'Success Get Detail Rent.', 200, $rent); 
            
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function update(Request $request, $id)
    {
       
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
        ], [
            'status.required' => 'Please Input Request status.',
            'status.in' => 'There is no choice of that status.',
        ]);
        
        if ($validator->fails()) {
            return ResponseJson::response('failed', 'Error Validation', 422, ['error' => $validator->errors()]);
        }
        
        $rent = Rent::where('id', $id)->where('status', 'unapproved')->first();
        
        if (!$rent) {
            return ResponseJson::response('failed', 'Rent Not Found.', 404, null);
        }
        
        DB::beginTransaction();
        
        try {
            $rent->status = $request->status;
            
            if ($request->status == 'rejected') {
                $validator = Validator::make($request->all(), [
                    'notes' => 'required',
                ], [
                    'notes.required' => 'Please Input Notes.',
                ]);
        
                if ($validator->fails()) {
                    return ResponseJson::response('failed', 'Error Validation', 422, ['error' => $validator->errors()]);
                }
        
                $rent->notes = $request->notes;
            }
            
            $rent->save();
            DB::commit();
            return ResponseJson::response('success', 'Success Update Status Rent.', 200, null);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
    }

    public function currentMeeting($room_id)
    {
        try{
            $date_now = Carbon::now()->format('Y-m-d');
            $time_now = Carbon::now()->format('H:i:s');
            $check_rent = Rent::where('room_id', $room_id)
                ->where('date_start', $date_now)
                ->where('time_start', '<=', $time_now)
                ->where('time_end', '>=', $time_now)
                ->where('status', 'approved')
                ->first();

            return ResponseJson::response('success', 'Get List Current Meeting.', 200, $check_rent);
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
    }
}
