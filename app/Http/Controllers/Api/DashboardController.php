<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\MasterRoom;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $now = Carbon::now();
        $dateNow = $now->format('Y-m-d H:i:s');
        $todayDate = $now->format('Y-m-d');
        $threeDaysAgo = $now->addDays(3)->format('Y-m-d');

        $statistic = [
            'total_room' => MasterRoom::select('id')->count(),
            'total_rent' => Rent::select('id')->whereIn('status', ['approved', 'done'])->count(),
            'total_user' => User::select('id')->where('role', 'user')->count(),
            'event_done' => Rent::select('id')->where('status', 'done')->count(),
            'event_expired' => Rent::select('id')->where('status', 'expired')->count(),
            'event_unapproved' => Rent::select('id')->where('status', 'unapproved')->count(),
        ];
        $expiredRentals = Rent::join('user_details as udr', 'udr.user_id', '=', 'rents.user_id')
            ->select('event_name', 'date_start', 'time_start','udr.name as user_responsible')
            ->where('status', 'unapproved')
            ->whereDate('date_start', '<=',$threeDaysAgo)
            ->get()
            ->toArray();
        $todayEvents = Rent::join('master_rooms as mr', 'mr.id', '=', 'rents.room_id')
            ->join('user_details as udr', 'udr.user_id', '=', 'rents.user_id')
            ->join('users as u', 'u.id', '=', 'rents.user_id')
            ->whereDate('date_start', $todayDate)
            ->whereIn('rents.status', ['approved', 'done'])
            ->select('event_name', 'udr.name as user_responsible', 'u.email', 'mr.room_name', 'room_capacity', 'guest_count', 'rents.status', 'date_start', 'date_end', 'time_start', 'time_end')
            ->get()
            ->map(function ($event) use ($dateNow) {
                $eventStartTime = $event->date_start . ' ' . $event->time_start;
                $eventEndTime = $event->date_end . ' ' . $event->time_end;
        
                if ($eventStartTime <= $dateNow && $eventEndTime >= $dateNow) {
                    $status = 'ongoing';
                } elseif ($event->status === 'done') {
                    $status = 'completed';
                } else {
                    $status = 'waiting';
                }
        
                return [
                    'event_name' => $event->event_name,
                    'user_responsible' => $event->user_responsible,
                    'user_email' => $event->email,
                    'room_name' => $event->room_name,
                    'room_capacity' => $event->room_capacity,
                    'guest_count' => $event->guest_count,
                    'date_start' => $event->date_start,
                    'date_end' => $event->date_end,
                    'status' => $status,
                    'time_start' => $event->time_start,
                    'time_end' => $event->time_end,
                ];
            });

        $data = [
            'statistic' => $statistic,
            'nearly_expired' => $expiredRentals,
            'today_events' => $todayEvents,
        ];

        return ResponseJson::response('success', 'Success Get Dashboard', 200, $data);
    }
}
