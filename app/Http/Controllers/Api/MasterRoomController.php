<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\MasterRoom;
use DataTables;
use Validator;
use DB;
use Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class MasterRoomController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    public function index()
    {
        try{
            $check_user = Auth::user();
            if($check_user->role != "Admin"){
                return ResponseJson::response('failed', 'You not have access!', 403, null); 
            }
            $fetch = MasterRoom::orderBy('created_at', 'DESC')
                ->get()
                ->toArray();
            $i = 0;
            $reform = array_map(function($new) use (&$i) { 
                $data = [
                    'room_id' => $new['id']
                ];
                
                $qrCode = QrCode::size(300)->generate(json_encode($data));
                $base64 = 'data:image/png;base64,' . base64_encode($qrCode);
                $i++;
                return [
                    'no' => $i.'.',
                    'id' => $new['id'],
                    'room_name' => $new['room_name'],
                    'room_desc' => $new['room_desc'],
                    'room_capacity' => $new['room_capacity'],
                    'qrcode' => $base64,
                    'created_at' => $new['created_at'],
                ]; 
            }, $fetch);
            
            $datatables =  DataTables::of($reform)->make(true);
            $data = array(
                'draw' => $datatables->original['draw'],
                'recordsTotal' => $datatables->original['recordsTotal'],
                'recordsFiltered' => $datatables->original['recordsFiltered'],
                'data' => $datatables->original['data']
            );
            return ResponseJson::response('success', 'Success Get List Room.', 200, $data); 
            
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function store(Request $request)
    {
        $check_user = Auth::user();
        if($check_user->role != "Admin"){
            return ResponseJson::response('failed', 'You not have access!', 403, null); 
        }
        $validator = Validator::make($request->all(), [
            'room_name' => 'required|string',
            'room_desc' => 'required|string',
            'room_capacity' => 'required|numeric',
        ],[
            'room_name' => 'Please Input Request room_name.', 
            'room_desc' => 'Please Input Request room_desc.',
            'room_capacity' => 'Please Input Request room_capacity.'
        ]);
        if ($validator->fails()) {
            return ResponseJson::response('failed', 'Error Validation', 422, ['errpr' => $validator->errors()]);
        }
        DB::beginTransaction();
        try{
            $store = new MasterRoom();
            $store->room_name = $request->room_name;
            $store->room_desc = $request->room_desc;
            $store->room_capacity = $request->room_capacity;
            $store->save();
            DB::commit();
            return ResponseJson::response('success', 'Success Create Room.', 200, null); 
            
        }catch(\Exception $e){
            DB::rollback();
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function detail($id)
    {
        $check_user = Auth::user();
        if($check_user->role != "Admin"){
            return ResponseJson::response('failed', 'You not have access!', 403, null); 
        }
        try{
            $room = MasterRoom::where('id', $id)
                ->first();
            if(!$room){
                return ResponseJson::response('failed', 'Master Room Not Found.', 404, null); 
            }
            return ResponseJson::response('success', 'Success Get Detail Master Room.', 200, $room); 
            
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function update(Request $request, $id)
    {
        $check_user = Auth::user();
        if($check_user->role != "Admin"){
            return ResponseJson::response('failed', 'You not have access!', 403, null); 
        }
        $room = MasterRoom::where('id', $id)
                ->first();
        if(!$room){
            return ResponseJson::response('failed', 'Master Room Not Found.', 404, null); 
        }

        DB::beginTransaction();
        try{

            $room->room_name = $request->room_name ?? $room->room_name;
            $room->room_desc = $request->room_desc ?? $room->room_desc;
            $room->room_capacity = $request->room_capacity ?? $room->room_capacity;
            $room->save();

            DB::commit();
            return ResponseJson::response('success', 'Success Update Master Room.', 200, $room); 
        }catch(\Exception $e){
            DB::rollback();
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function destroy($id)
    {
        $check_user = Auth::user();
        if($check_user->role != "Admin"){
            return ResponseJson::response('failed', 'You not have access!', 403, null); 
        }
        $room = MasterRoom::where('id', $id)
                ->first();
        if(!$room){
            return ResponseJson::response('failed', 'Master Room Not Found.', 404, null); 
        }

        DB::beginTransaction();
        try{
            $room->delete();
            DB::commit();
            return ResponseJson::response('success', 'Success Delete Master Room.', 200, null); 
        }catch(\Exception $e){
            DB::rollback();
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function createQrcode($room_id)
    {
        $check_room = MasterRoom::where('id', $room_id)
            ->select('id')
            ->first();
        if(!$check_room){
            return ResponseJson::response('failed', 'Master Room Not Found.', 400, null); 
        }
        $data = [
            'room_id' => $room_id
        ];
        
        $qrCode = QrCode::size(300)->generate(json_encode($data));
        $base64 = 'data:image/png;base64,' . base64_encode($qrCode);
        
        return ResponseJson::response('success', 'Success Delete Master Room.', 200, ['qrcode' => $base64]); 
    }
}
