<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\MasterRoom;
use App\Models\Rent;
use App\Models\User;
use DB;
use Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Facades\Image;


class RentController extends Controller
{
    // public function __construct() {
    //     $this->middleware('auth:api');
    // }
    public function index(Request $request)
    {
        $data = QrCode::size(512)
        // ->format('png')
        // ->merge('https://png.pngtree.com/png-vector/20221018/ourmid/pngtree-twitter-social-media-round-icon-png-image_6315985.png')
        ->errorCorrection('M')
        ->generate(
            'https://twitter.com/HarryKir',
        );

    $data2 = response($data);

    return base64_encode($data2);

        try{
            $rents = Rent::when($request->status, function($query) use ($request) {
                    return $query->where('status', $request->status);
                })
                ->get();
            return ResponseJson::response('success', 'Success Get List Rent.', 200, $rents); 
            
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, $e->getMessage()); 
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
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, $e->getMessage()); 
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
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, $e->getMessage()); 
        }
    }

    public function detail($id)
    {
        try{
            $rent = Rent::where('id', $id)
                ->first();
            if(!$rent){
                return ResponseJson::response('failed', 'Rent Not Found.', 404, null); 
            }
            return ResponseJson::response('success', 'Success Get Detail Rent.', 200, $rent); 
            
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, $e->getMessage()); 
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
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, $e->getMessage());
        }
    }

    private function generateQRCodeBase64($data, $size = 200) {
        try {
            // Generate the QR code as a binary string
            $qrCodeImage = QrCode::size($size)->generate($data);
    
            // Create an Intervention Image from the binary string
            $image = Image::make($qrCodeImage);
    
            // Convert the Intervention Image to Base64 representation
            $base64Image = $image->encode('data-url')->encoded;
    
            return $base64Image;
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            // For debugging purposes, you can also use var_dump($e->getMessage());
            return null;
        }
    }
}
