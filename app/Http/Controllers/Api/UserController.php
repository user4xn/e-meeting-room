<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Mail\EmailVerification;
use App\Models\Rent;
use App\Models\User;
use App\Models\UserDetail;
use Crypt;
use DB;
use Auth;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use DataTables;
use Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try{
            $check_user = Auth::user();
            if(empty($check_user)) {
                return ResponseJson::response('failed', 'Unauthorized', 401, null);
            }
            if($check_user->role != "Admin"){
                return ResponseJson::response('failed', 'You not have access!', 403, null); 
            }
            $fetch = User::with('userDetail')
                ->orderBy('created_at', 'DESC')
                ->where('role', 'User')
                ->get()
                ->toArray();
            $i = 0;
            $reform = array_map(function($new) use (&$i) { 
                $i++;
                return [
                    'no' => $i.'.',
                    'id' => $new['id'],
                    'username' => $new['username'],
                    'email' => $new['email'],
                    'user_detail' => $new['user_detail']
                ]; 
            }, $fetch);
            
            $datatables =  DataTables::of($reform)->make(true);
            $data = array(
                'draw' => $datatables->original['draw'],
                'recordsTotal' => $datatables->original['recordsTotal'],
                'recordsFiltered' => $datatables->original['recordsFiltered'],
                'data' => $datatables->original['data']
            );
            return ResponseJson::response('success', 'Success Get List User.', 200, $data); 
            
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, $e->getMessage()); 
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $check_user = Auth::user();
            if(empty($check_user)) {
                return ResponseJson::response('failed', 'Unauthorized', 401, null);
            }
            if($check_user->role != "Admin"){
                return ResponseJson::response('failed', 'You not have access!', 403, null); 
            }
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'email' => 'required|unique:users,email',
                'password' => 'required|string',
                'nik' => 'required|unique:user_details,nik',
                'name' => 'required|string',
                'phone_number' => 'required|string',
                'address' => 'required|string'
            ], [
                'username.required' => 'Please input username.',
                'email.required' => 'Please input email.',
                'email.unique' => 'Email already taken.',
                'password.required' => 'Please input password.',
                'password.numeric' => 'Password must be numeric.',
                'nik.required' => 'Please input nik.',
                'nik.unique' => 'Nik already taken.',
                'name.required' => 'Please input name.',
                'phone_number.required' => 'Please input phone number.',
                'address.required' => 'Please input address.',
            ]);
            if ($validator->fails()) {
                return ResponseJson::response('failed', 'Error Validation', 422, ['error' => $validator->errors()]);
            }

            $store_user = new User();
            $store_user->username = $request->username;
            $store_user->email = $request->email;
            $store_user->password = bcrypt($request->password);
            $store_user->role = "User";
            $store_user->save();
            $store_user->fresh();

            $store_user_detail = new UserDetail();
            $store_user_detail->user_id = $store_user->id;
            $store_user_detail->nik = $request->nik;
            $store_user_detail->name = $request->name;
            $store_user_detail->phone_number = $request->phone_number;
            $store_user_detail->address = $request->address;
            $store_user_detail->save();

            $data = array(
                'user_id' => $store_user->id,
                'username' => $store_user->username,
                'email' => $store_user->email,
                'name' => $store_user_detail->name,
            );
            DB::commit();
            return ResponseJson::response('success', 'Success Store User.', 200, $data); 
            
        }catch(\Exception $e){
            DB::rollback();
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $check_user = Auth::user();
            if(empty($check_user)) {
                return ResponseJson::response('failed', 'Unauthorized', 401, null);
            }
            if($check_user->role != "Admin"){
                return ResponseJson::response('failed', 'You not have access!', 403, null); 
            }
            $check_user = User::find($id);
            if(!$check_user){
                return ResponseJson::response('failed', 'User Not Found!', 404, null); 
            }
            $check_user_detail = UserDetail::where('user_id', $check_user->id)
                ->first();
            $user_detail_id = ($check_user_detail) ? $check_user_detail->id : 0;
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'email' => 'required|unique:users,email,' . $check_user->id,
                'password' => 'required|numeric',
                'nik' => 'required|unique:user_details,nik,' . $user_detail_id,
                'name' => 'required|string',
                'phone_number' => 'required|string',
                'address' => 'required|string'
            ], [
                'username.required' => 'Please input username.',
                'email.required' => 'Please input email.',
                'email.unique' => 'Email already taken.',
                'password.required' => 'Please input password.',
                'password.numeric' => 'Password must be numeric.',
                'nik.required' => 'Please input nik.',
                'nik.unique' => 'Nik already taken.',
                'name.required' => 'Please input name.',
                'phone_number.required' => 'Please input phone number.',
                'address.required' => 'Please input address.',
            ]);
            if ($validator->fails()) {
                return ResponseJson::response('failed', 'Error Validation', 422, ['error' => $validator->errors()]);
            }

            $check_user->username = $request->username;
            $check_user->email = $request->email;
            $check_user->password = bcrypt($request->password);
            $check_user->role = "User";
            $check_user->save();

            if($check_user_detail){
                $check_user_detail->nik = $request->nik;
                $check_user_detail->name = $request->name;
                $check_user_detail->phone_number = $request->phone_number;
                $check_user_detail->address = $request->address;
                $check_user_detail->save();     
            }else{
                $check_user_detail = new UserDetail();
                $check_user_detail->user_id = $id;
                $check_user_detail->nik = $request->nik;
                $check_user_detail->name = $request->name;
                $check_user_detail->phone_number = $request->phone_number;
                $check_user_detail->address = $request->address;
                $check_user_detail->save();
                $check_user_detail->fresh();
            }

            $data = array(
                'user_id' => $check_user->id,
                'username' => $check_user->username,
                'email' => $check_user->email,
                'name' => $check_user_detail->name,
            );
            DB::commit();
            return ResponseJson::response('success', 'Success Update User.', 200, $data); 
        }catch(\Exception $e){
            DB::rollback();
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $check_user = Auth::user();
            if(empty($check_user)) {
                return ResponseJson::response('failed', 'Unauthorized', 401, null);
            }
            if($check_user->role != "Admin"){
                return ResponseJson::response('failed', 'You not have access!', 403, null); 
            }
            $check_user = User::find($id);
            if(!$check_user){
                return ResponseJson::response('failed', 'User Not Found!', 404, null); 
            }
            $check_rent = Rent::where('user_id', $id)
                ->select('id')
                ->get();
            if(count($check_rent) > 0){
                return ResponseJson::response('success', 'Can`t Delete User.', 400, null); 
            }
            $check_user->delete();
            UserDetail::where('user_id', $id)->delete();
            DB::commit();
            return ResponseJson::response('success', 'Success Delete User.', 200, null); 
        }catch(\Exception $e){
            DB::rollback();
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function getProfileUser(Request $request)
    {
        try{
            $check = Auth::user();
            if(empty($check)) {
                return ResponseJson::response('failed', 'Unauthorized', 401, null);
            }
            $user = User::select('id', 'username', 'email','role', 'status')
                ->where('id', $request->User()->id)
                ->with('userDetail')
                ->first();
            return ResponseJson::response('success', 'Success Get Profile User', 200, $user);
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
    }

    public function emailVerification(Request $request)
    {
        DB::beginTransaction();
        try {
            $decrypted = Crypt::decrypt($request->data);
            $user = User::where('id', $decrypted)
                ->where('email_verified_at', null)
                ->first();

            if(!$user){
                return ResponseJson::response('failed', 'Data User Not Found.', 400, null);
            }
            $user->update([
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);
            DB::commit();
            return ResponseJson::response('success', 'Success Verification', 200, null);
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
    }

    public function resendEmailVerification(Request $request)
    {
        try {
            $email = $request->email;
            $check_user = User::where('email', $email)
                ->where('email_verified_at', null)
                ->select('id', 'email_verified_at')
                ->first();
                
            if(!$check_user){
                return ResponseJson::response('failed', 'Data User Not Found.', 400, null);
            }

            Mail::to($email)->send(new EmailVerification($check_user->id));
            return ResponseJson::response('success', 'Success Resend Email Verification.', 200, null);
        } catch (\Exception $e) {
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
    }
}
