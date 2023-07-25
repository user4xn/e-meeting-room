<?php

namespace App\Http\Middleware;

use App\Models\Session;
use Closure;
use DB;
use Auth;

class SingleSessionLogin
{
    public function handle($request, Closure $next)
    {

        if (auth()->check()) {
            $session_id = session()->getId();
            $user_id = auth()->user()->id;

            $user_session = DB::table('sessions')
                ->where('user_id', $user_id)
                ->first();
            
            if((Auth::user()->status == "nonactive") ||  (Auth::user()->email_verified_at == null)){
                auth()->logout();
                return redirect()->route('login')->with('session_expired', true);
            }
            if ($user_session && $user_session->id !== $session_id) {
                auth()->logout();
                return redirect()->route('not-authorized')->with('session_expired', true);
            }
           
            if (!$user_session) {

                $ipAddress = "";
                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $ipAddress = $_SERVER['REMOTE_ADDR'];
                }

                DB::table('sessions')->insert([
                    'id' => $session_id,
                    'user_id' => $user_id,
                    'ip_address' => $ipAddress,
                    'user_agent' => $request->header('User-Agent'),
                    'last_activity' => now()
                ]);
            }
        }

        return $next($request);
    }
}
