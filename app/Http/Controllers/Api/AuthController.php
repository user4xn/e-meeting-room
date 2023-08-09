<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Mail\EmailOtpLogin;
use App\Models\User;
use Validator;
use App\Mail\EmailVerification;
use App\Models\UserEmailOtp;
use App\Models\UserLog;
use Illuminate\Support\Facades\Mail;
use DB;
use Auth;
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
            $email = $user->email;
            $user_id = $user->id;
            if($user->email_verified_at == null){
                Mail::to($email)->send(new EmailVerification($user_id));
                return ResponseJson::response('failed', 'Please Verification Email', 400, null);
            }
            $check_log = $this->checkUserLogin($request, $email);
            if($check_log == "Please Verification OTP"){
                return ResponseJson::response('failed', 'Please Verification Login With OTP.', 400, null);
            }
            if($check_log == "new ip"){
                $data = array(
                    'data_auth' => $this->createNewToken($token)->original,
                    'is_verification_otp' => true,
                );
                return ResponseJson::response('success', 'login success', 200, $data);
            }
            $data = array(
                'data_auth' => $this->createNewToken($token)->original,
                'is_verification_otp' => false,
            );
            return ResponseJson::response('success', 'login success', 200, $data);
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, $e->getMessage() );
        }
    }

    public function resendOtpEmail(Request $request)
    {
        $email = $request->email;
        $check_user = User::where('email', $email)
            ->select('id')
            ->first();
        if(!$check_user){
            return ResponseJson::response('failed', 'No Data User.', 404, null);
        }
        $user_id = $check_user->id;
        Mail::to($email)->send(new EmailOtpLogin($user_id));
        return ResponseJson::response('success', 'Success Resend OTP Email.', 200, null);
    }

    public function verificationEmailOtp(Request $request)
    {
        $email = $request->email;
        $otp = $request->otp;
        $check_user = User::where('email', $email)
            ->select('id')
            ->first();
        if(!$check_user){
            return ResponseJson::response('failed', 'No Data User.', 404, null);
        }
        $check_otp = UserEmailOtp::where('id', $check_user->id)
            ->where('otp', $otp)
            ->select('id')
            ->first();
        if(!$check_otp){
            return ResponseJson::response('failed', 'Missing Otp Email.', 400, null);
        }

        UserEmailOtp::where('id', $check_otp->id)
            ->update([
                'is_verif' => 1,
            ]);
        return ResponseJson::response('success', 'Success Verification Otp Email.', 200, null);
    }
    private function checkUserLogin($request, $email)
    {
        $check_otp = UserEmailOtp::where('user_id', Auth::user()->id)
            ->where('is_verif', 0)
            ->select('id')
            ->first();
        if($check_otp){
            return "Please Verification OTP";
        }
        $ipAddress = "";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        $check = UserLog::where('ip_address', $ipAddress)
            ->select('id')
            ->first();
        $user_id = Auth::user()->id;
        if(!$check){
            $log = new UserLog();
            $log->user_id = Auth::user()->id;
            $log->ip_address = $ipAddress;
            $log->user_agent = $request->header('User-Agent');
            $log->save();
            Mail::to($email)->send(new EmailOtpLogin($user_id));
            return "new ip";
        }else{
            return "existing ip";
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
