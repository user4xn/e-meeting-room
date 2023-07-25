<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use ErrorException;
use Alert;
use App\Models\User;
use App\Models\NotificationUser;
use Log;
use DB;

class UtilsController extends Controller
{
    public function notifyPassword()
    {
        $fetchUsers = User::whereRaw('
            (last_password_changed IS NOT NULL AND last_password_changed <= DATE_SUB(NOW(), INTERVAL 3 MONTH))
            OR 
            (created_at <= DATE_SUB(NOW(), INTERVAL 3 MONTH))
        ')->get();

        DB::beginTransaction();
        try {

            foreach ($fetchUsers as $user) {
                $title = 'Saatnya Mengganti Sandi!';
                if($user->last_password_changed == null) {
                    $last_changed = $this->indoDate(date("Y-m-d", strtotime($user->created_at)));
                    $body = 'Anda belum pernah mengganti sandi sejak pertama kali akun dibuat, yaitu <b>'.$last_changed.'</b>. Untuk alasan keamanan, harap ganti sandi anda dalam 3 bulan sekali.';
                } else {
                    $last_changed = $this->indoDate(date("Y-m-d", strtotime($user->last_password_changed)));
                    $body = 'Terakhir anda mengganti sandi adalah <b>'.$last_changed.'</b>. Untuk alasan keamanan, harap ganti sandi anda dalam 3 bulan sekali.';
                } 
    
                $notification = new Notificationuser();
                $notification->user_id = $user->id;
                $notification->title = $title;
                $notification->notification = $body;
                $notification->save();
            }

            DB::commit();
            return 'very good';
        } catch (\Throwable $th) {
            DB::rollback();
            Log::info($th->getMessage());
            return $th->getMessage();
        }
    }

    function indoDate($date){
        $month = array (
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $explode = explode('-', $date);
     
        return $explode[2] . ' ' . $month[ (int)$explode[1] ] . ' ' . $explode[0];
    }
}
