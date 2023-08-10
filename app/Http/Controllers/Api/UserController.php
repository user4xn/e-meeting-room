<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Mail\EmailVerification;
use App\Models\User;
use Crypt;
use DB;
use Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
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
