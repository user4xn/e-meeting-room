<?php

namespace App\Helpers;
use App\Models\ActivityLog;
use Auth;
use DB;
class ActivityLogUser
{

    public static function insert($log_name, $description)
    {
        $ipAddress = "";
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];  
        }  
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];  
        }  else{  
            $ipAddress = $_SERVER['REMOTE_ADDR'];  
        }   

        $user = Auth::user();
        $activity = new ActivityLog();
        $activity->user_id = $user->id;
        $activity->log_name = $log_name;
        $activity->description = $description;
        $activity->ip_address = $ipAddress;
        $activity->save();
    }

}