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
use Session;
use Crypt;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required|string|min:6',
            ], [
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
            $token_jwt = JWTAuth::fromUser($user);
            $check_log = $this->checkUserLogin($request, $email);
            if ($check_log == "new ip") {
                $data = array(
                    'data_auth' => $this->createNewToken($token_jwt)->original,
                    'is_verification_otp' => true,
                );
                return ResponseJson::response('success', 'login success', 200, $data);
            }
            $data = array(
                'data_auth' => $this->createNewToken($token_jwt)->original,
                'is_verification_otp' => false,
            );
            return ResponseJson::response('success', 'login success', 200, $data);
        } catch (\Exception $e) {
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
    }

    public function resendOtpEmail(Request $request)
    {
        $email = $request->email;
        $check_user = User::where('email', $email)
            ->select('id')
            ->first();
        if (!$check_user) {
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
        if (!$check_user) {
            return ResponseJson::response('failed', 'No Data User.', 404, null);
        }
        $check_otp = UserEmailOtp::where('user_id', $check_user->id)
            ->where('otp', $otp)
            ->where('is_verif', 0)
            ->select('id')
            ->first();

        if (!$check_otp) {
            return ResponseJson::response('failed', 'Missing Otp Email.', 400, null);
        }
        $ipAddress = "";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }

        $log = new UserLog();
        $log->user_id = $check_user->id;
        $log->ip_address = $ipAddress;
        $log->user_agent = $request->header('User-Agent');
        $log->save();
        UserEmailOtp::where('id', $check_otp->id)
            ->update([
                'is_verif' => 1,
            ]);
        return ResponseJson::response('success', 'Success Verification Otp Email.', 200, null);
    }
    private function checkUserLogin($request, $email)
    {
        $ipAddress = "";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        $user_id = Auth::user()->id;
        $check = UserLog::where('ip_address', $ipAddress)
            ->where('user_id', $user_id)
            ->select('id')
            ->first();
        if (!$check) {
            $log = new UserLog();
            $log->user_id = Auth::user()->id;
            $log->ip_address = $ipAddress;
            $log->user_agent = $request->header('User-Agent');
            $log->save();
            // Mail::to($email)->send(new EmailOtpLogin($user_id));
            return "new ip";
        } else {
            return "existing ip";
        }
    }


    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 6000,
            'data' => auth()->user()
        ]);
    }

    public function logout()
    {

        try {
            Session::flush();
            Auth::logout();
            return ResponseJson::response('success', 'Success Logout.', 200, null);
        } catch (\Exception $e) {
            return ResponseJson::response('failed', 'Invalid Token!.', 500, ['error' => $e->getMessage()]);
        }
    }
    public function refresh(Request $request)
    {
       try{
        $token = JWTAuth::getToken();
        $newToken = JWTAuth::refresh($token, ['custom_claim' => 'value'], 10);
        $data = array(
            'access_token' => $newToken,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 6000,
            'data' => auth()->user()
        );

        return ResponseJson::response('success', 'Success Refresh Token.', 200, $data);

       }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error!.', 500, ['error' => $e->getMessage()]);
       }
    }
    public function unauthorized(Request $request)
    {
        return ResponseJson::response('failed', 'Unauthorized', 401, null);
    }
}
