<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\User;
use Validator;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;
use DB;
use Crypt;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required|string|min:6',
            ],[
                'username' => 'Please Input Request Email.', 
                'password' => 'Please Input Request Password.'
            ]);
            if ($validator->fails()) {
                return ResponseJson::response('failed', 'Error Validation', 422, $validator->errors());
            }
            if (!$token = auth()->attempt($validator->validated())) {
                return ResponseJson::response('failed', 'Unauthorized', 401, null);
            }
            $user = $request->User();
            if($user->email_verified_at == null){
                $email = $user->email;
                $user_id = $user->id;
                Mail::to($email)->send(new EmailVerification($user_id));
                return ResponseJson::response('failed', 'Please Verification Email', 400, null);
            }
            return ResponseJson::response('success', 'login success', 200, $this->createNewToken($token)->original);
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, $e->getMessage() );
        }
    }

    public function getProfileUser(Request $request)
    {
        try{
            $user = User::select('id', 'username', 'role', 'status')
                ->where('id', $request->User()->id)
                ->with('userDetail')
                ->first();
            return ResponseJson::response('success', 'Success Get Profile User', 200, $user);
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, $e->getMessage());
        }
    }

    public function unauthorized(Request $request)
    {
        return ResponseJson::response('failed', 'Unauthorized', 401, null);
    }

    public function emailVerification(Request $request)
    {
        try {
            $decrypted = Crypt::decrypt($request->data);
            $user = User::findOrFail($decrypted);
            if($user->email_verified_at == null){
                $user->update([
                    'email_verified_at' => date('Y-m-d H:i:s')
                ]);
            }
            DB::commit();
            return ResponseJson::response('success', 'Success Verification', 200, null);
        } catch (\Exception $e) {
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, $e->getMessage());
        }
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'data' => auth()->user()
        ]);
    }
}
