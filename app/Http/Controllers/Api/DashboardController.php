<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $total_rent_requested = Rent::where('status', 'unapproved')
            ->select('id')
            ->count();
        $total_user = User::where('role', 'User')
            ->select('id')
            ->count();
        
        $data = array(
            'total_rent_requested' => $total_rent_requested,
            'total_user' => $total_user
        );

        return ResponseJson::response('failed', 'Success Get Dashboard', 200, $data);
    }
}
