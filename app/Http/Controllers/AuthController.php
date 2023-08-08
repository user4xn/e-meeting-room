<?php

namespace App\Http\Controllers;

use App\Models\AbilityMenu;
use App\Models\AbilityUser;
use Illuminate\Http\Request;
use ErrorException;
use App\Models\LogLogin;
use App\Mail\EmailVerification;
use Mail;
use Auth;
use Session;
use DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Rules\ValidHCaptcha;

class AuthController extends Controller
{
    
    public function login()
    {
        return response()->json([
            'message' => 'not authorized'
        ], 401);
    }

    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            
            if(Auth::user()->email_verified_at == null){
                $hash = Crypt::encrypt($request->email);
                $user = Auth::user();
                Mail::to($user->email)->send(new EmailVerification($user->id));
                return redirect()->route('resend-verification', $hash);
            }

            DB::table('sessions')->where('user_id', Auth::user()->id)->delete();
            $ipAddress = "";
            if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                $ipAddress = $_SERVER['HTTP_CLIENT_IP'];  
            }  
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];  
            }  else{  
                $ipAddress = $_SERVER['REMOTE_ADDR'];  
            }   
            if(!empty($ipAddress)) {
                $log = new LogLogin();
                $log->user_id = Auth::user()->id;
                $log->ip_address = $ipAddress;
                $log->user_agent = $request->header('User-Agent');
                $log->save();
            }
            
            return redirect()->route('dashboard');
        }
  
        return redirect("login")->withErrors('Email atau sandi yang anda masukan salah');
    }

    public function logOut() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }

    public function successVerification()
    {
        return view('pages.emails.success_verification');
    }

    public function resendVerification($hash)
    {
        $decrypted = Crypt::decrypt($hash);
        $user = User::where('email', $decrypted)->first();
        $user_id = $user->id;
        Mail::to($user->email)->send(new EmailVerification($user_id));

        return view('pages.emails.resend_verification', compact('user', 'hash'));
    }

    public function emailVerification($hash)
    {
        try {
            $decrypted = Crypt::decrypt($hash);
            $user = User::findOrFail($decrypted);

            if($user->email_verified_at == null){
                $user->update([
                    'email_verified_at' => date('Y-m-d H:i:s')
                ]);
            }
            return redirect()->route('success-verification');
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function notAuthorized()
    {
        return view('pages.errors.401');
    }

}
