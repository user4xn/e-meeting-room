<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\MasterRoom;
use App\Models\Rent;
use Carbon\Carbon;

class LandingpageController extends Controller
{
    public function listRoom()
    {
        $master_rooms = MasterRoom::select('id', 'room_name')
            ->get()
            ->toArray();
        return ResponseJson::response('success', 'Success Get List Room.', 200, $master_rooms);
    }

    public function listCurrentRent($room_id)
    {
        $date_now = Carbon::now()->format('Y-m-d');
        $time_now = Carbon::now()->format('H');
        $current_event = Rent::where('room_id', $room_id)
            ->where('date_start',$date_now)
            ->whereHour('time_start', $time_now)
            ->first();
        $next_events = Rent::where('room_id', $room_id)
            ->whereDate('date_start', $date_now)
            ->where('status', 'approved')
            ->select('id', 'room_id', 'event_name', 'date_start', 'date_end', 'time_start', 'time_end')
            ->orderBy('date_start', 'ASC')
            ->get()
            ->toArray();

        $data = array(
            'current_event' => $current_event, 
            'list_next_event' => $next_events 
        );
        return ResponseJson::response('success', 'Success Get Events.', 200, $data);
    }
}
