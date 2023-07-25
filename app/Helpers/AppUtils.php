<?php


use App\Models\NotificationUser;
use App\Models\Role;
use App\Models\AppConfig;
use Carbon\Carbon;

function getNotification()
{
    if (Auth::check()) {
        $fetch = NotificationUser::where(['user_id' => Auth::user()->id])
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();
            
        $reformed = [];
        $unread = 0;

        Carbon::setLocale('id');
        foreach ($fetch as $data) {
            $timestamp = Carbon::parse($data->created_at)->diffForHumans();
            $reformed[] = [
                'id' => $data->id,
                'title' => $data->title,
                'notification' => $data->notification,
                'is_read' => $data->is_read,
                'created_at' => $timestamp,
            ];

            if($data->is_read == 0) {
                $unread++;
            }
        }
        
        $response = [
            'data' => $reformed,
            'unread' => $unread
        ];
        return $response;
    }
}

function roleDetail()
{
    return Role::where('id', Auth::user()->role_id)->first();
}

function appDetail()
{
    return AppConfig::first();
}
