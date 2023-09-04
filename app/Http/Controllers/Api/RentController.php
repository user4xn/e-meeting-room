<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\LogRent;
use App\Models\MasterRoom;
use App\Models\Rent;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class RentController extends Controller
{
    public function index(Request $request)
    {
        try {
            if(Auth::user()->role != "Admin"){
                $fetch = Rent::when($request->status, function ($query) use ($request) {
                    return $query->where('status', $request->status);
                })
                ->when($request->search, function ($query) use ($request) {
                    return $query->where('event_name', 'LIKE','%'.$request->search.'%');
                })
                ->where('user_id', Auth::user()->id)
                ->get()
                ->toArray();
            }else{
                $fetch = Rent::when($request->status, function ($query) use ($request) {
                    return $query->where('status', $request->status);
                })
                ->when($request->search, function ($query) use ($request) {
                    return $query->where('event_name', 'LIKE','%'.$request->search.'%');
                })
                ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                    return $query->whereDate('date_start', '>=',$request->start_date)
                        ->whereDate('date_end', '<=',$request->end_date);
                })
                ->leftjoin('user_details as ud', 'ud.user_id', '=', 'rents.user_id')
                ->leftjoin('user_details as vud', 'vud.user_id', '=', 'rents.verificator_user_id')
                ->select('ud.name as user_name', 'ud.phone_number as user_phone', 'vud.name as verificator_name', 'vud.phone_number as verificator_phone','rents.*')
                ->get()
                ->toArray();
            }
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
                    'date_start' => $new['date_start'].' '.$new['time_start'],
                    'date_end' => $new['date_end'].' '.$new['time_end'],
                    'time_start' => $new['time_start'],
                    'time_end' => $new['time_end'],
                    'event_name' => $new['event_name'],
                    'event_desc' => $new['event_desc'],
                    'guest_count' => $new['guest_count'],
                    'organization' => $new['organization'],
                    'status' => $new['status'],
                    'notes' => $new['notes'],
                    'qrcode' => asset('qrcodes/qrcode_'.$name_file.'.svg'),
                    'user_name' => $new['user_name'],
                    'user_phone' => $new['user_phone'],
                    'verificator_name' => $new['verificator_name'],
                    'verificator_phone' => $new['verificator_phone'],
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
            $fetch = Rent::whereIn('status', ['approved', 'unapproved', 'expired'])
                ->get()
                ->toArray();

            $i = 0;
            $reform = array_map(function($new) use (&$i) { 
                $i++;
                return [
                    'id' => $new['id'],
                    'url' => '',
                    'title' => $new['event_name'],
                    'start' => $new['date_start'].' '.$new['time_start'],
                    'end' => $new['date_end'].' '.$new['time_end'],
                    'allDay' => $new['is_all_day'] == 1 ? true : false,
                    'extendedProps' => array(
                        'calendar' => ($new['status'] == "approved") ? $new['organization'] : ucwords($new['status'])
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
            'datetime_start' => 'required',
            'datetime_end' => 'required',
            'event_name' => 'required',
            'event_desc' => 'required',
            'guest_count' => 'required',
            'organization' => 'required',
            'is_all_day' => 'required',
        ],[
            'user_id' => 'Please Input Request user_id.', 
            'room_id' => 'Please Input Request room_id.',
            'datetime_start' => 'Please Input Request date_start.',
            'datetime_end' => 'Please Input Request datetime_end.',
            'event_name' => 'Please Input Request event_name.',
            'event_desc' => 'Please Input Request event_desc.',
            'guest_count' => 'Please Input Request guest_count.',
            'organization' => 'Please Input Request organization.',
            'is_all_day' => 'Please Input Request is_all_day.',
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

        $date_start = Carbon::parse($request->datetime_start)->format('Y-m-d');
        $date_end = Carbon::parse($request->datetime_end)->format('Y-m-d');
        $time_start = Carbon::parse($request->datetime_start)->format('H:i:s');
        $time_end = Carbon::parse($request->datetime_end)->format('H:i:s');

        $startTime = Carbon::parse($request->datetime_start);
        $endTime = Carbon::parse($request->datetime_end);

        $interval = $startTime->diff($endTime);
        $totalHours = $interval->h + ($interval->days * 24);

        DB::beginTransaction();
        try{
            $store_rent = new Rent();
            $store_rent->user_id = $request->user_id;
            $store_rent->room_id = $request->room_id;
            $store_rent->date_start = $date_start;
            $store_rent->date_end = $date_end;
            $store_rent->time_start = $time_start;
            $store_rent->time_end = $time_end;
            $store_rent->event_name = $request->event_name;
            $store_rent->event_desc = $request->event_desc;
            $store_rent->guest_count = $request->guest_count;
            $store_rent->organization = $request->organization;
            $store_rent->is_all_day = ($request->is_all_day == 1 && $totalHours >= 24 || $totalHours >= 24) ? 1 : 0;
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
            if(Auth::user()->role != "Admin"){
                $user_id = Auth::user()->id;
                $rent = Rent::where('id', $id)
                    ->where('user_id', $user_id)
                    ->with('Room')
                    ->first();
            }else{
                $rent = Rent::where('id', $id)
                    ->with('Room')
                    ->first();
            }
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

        if(Auth::user()->role != "Admin"){
            $user_id = Auth::user()->id;
            $rent = Rent::where('id', $id)
                ->where('user_id', $user_id)
                ->where('status', 'unapproved')
                ->first();
        }else{
            $rent = Rent::where('id', $id)
                ->where('status', 'unapproved')
                ->first();
        }
        
        if (!$rent) {
            return ResponseJson::response('failed', 'Rent Not Found.', 404, null);
        }
        
        DB::beginTransaction();
        
        try {

            $date_start = "";
            $time_start = "";
            $date_end = "";
            $time_end = "";
            $totalHours = 0;
            if($request->datetime_start){
                $date_start = Carbon::parse($request->datetime_start)->format('Y-m-d');
                $time_start = Carbon::parse($request->datetime_start)->format('H:i:s');
            }

            if($request->datetime_end){
                $date_end = Carbon::parse($request->datetime_end)->format('Y-m-d');
                $time_end = Carbon::parse($request->datetime_end)->format('H:i:s');
            }

            if($request->datetime_start && $request->datetime_end){
                $startTime = new DateTime($request->datetime_start);
                $endTime = new DateTime($request->datetime_end);

                $interval = $startTime->diff($endTime);
                $totalHours = $interval->h + ($interval->days * 24);
            }

            $rent->user_id = $request->user_id ?? $rent->user_id;
            $rent->room_id = $request->room_id ?? $rent->room_id;
            $rent->date_start = $date_start ?? $rent->date_start;
            $rent->date_end = $date_end ?? $rent->date_end;
            $rent->time_start = $time_start ?? $rent->time_start;
            $rent->time_end = $time_end ?? $rent->time_end;
            $rent->event_name = $request->event_name ?? $rent->event_name;
            $rent->event_desc = $request->event_desc ?? $rent->event_desc;
            $rent->guest_count = $request->guest_count ?? $rent->guest_count;
            $rent->organization = $request->organization ?? $rent->organization;
            $rent->is_all_day = $totalHours >= 24 ? 1 : $rent->is_all_day;

            $rent->save();

            DB::commit();
            return ResponseJson::response('success', 'Success Update Status Rent.', 200, null);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
    }
    
    public function updateStatus(Request $request, $id)
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

        $rent = Rent::where('id', $id)
            ->where('status', 'unapproved')
            ->first();
        
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

            $rent->verificator_user_id = Auth::user()->id;
            
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

    public function delete($rent_id)
    {
        if(Auth::user()->role != "Admin"){
            $rent = Rent::where('id', $rent_id)
                ->first();
        }else{
            $rent = Rent::where('id', $rent_id)
                ->where('user_id', Auth::user()->id)
                ->first();
        }
        if (!$rent) {
            return ResponseJson::response('failed', 'Rent Not Found.', 404, null);
        }
        $rent->delete();
        return ResponseJson::response('success', 'Success Delete Rent.', 200, null);
    }

    public function selectOptionRoom()
    {
        $fetch = MasterRoom::select('id', 'room_name')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();
        return ResponseJson::response('success', 'Success Get Select Option Master Room.', 200, $fetch); 
    }

}
