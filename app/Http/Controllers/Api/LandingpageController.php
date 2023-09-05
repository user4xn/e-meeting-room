<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\MasterRoom;
use App\Models\Rent;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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
        $time_now = Carbon::now()->format('H:i:s');
        $room = MasterRoom::where('id', $room_id)
            ->select('id', 'room_name')
            ->first();
        if(!$room){
            return ResponseJson::response('failed', 'Master Room Not Found.', 404, null);
        }
        $url = request('url', env('FE_WEB_URL').'/room/scan/'.$room->id);
        $qrCode = QrCode::format('png')->size(200)->generate($url);
        $base64Image = 'data:image/png;base64,' . base64_encode($qrCode);
        $current_event = Rent::where('room_id', $room_id)
            ->where('date_start', '>=', $date_now)
            ->where('date_start', '<=', $date_now)
            ->where('time_start', '<=', $time_now)
            ->where('time_end', '>=', $time_now)
            ->first();

        $next_events = Rent::where('room_id', $room_id)
            ->whereDate('date_start', '<=', $date_now)
            ->whereDate('date_end', '>=', $date_now)
            ->where('time_start', '>=', $time_now)
            ->where('time_end', '>=', $time_now)
            ->where('status', 'approved')
            ->select('event_name', 'event_desc','date_start', 'date_end', 'time_start', 'time_end')
            ->orderBy('date_start', 'ASC')
            ->get()
            ->toArray();

        $data = array(
            'room' => [
                'room_name' => $room->room_name, 
                'qrcode' => $base64Image 
            ],
            'current_event' => $current_event,
            'next_events' => $next_events
        );

        return ResponseJson::response('success', 'Success Get Events.', 200, $data);
    }
}
