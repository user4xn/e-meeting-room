<?php

namespace App\Helpers;
use App\Models\ActivityLog;
use Auth;
use DB;
class ResponseJson
{
    public static function response($status, $message, $code, $data)
    {
        $response = array(
            'meta' => [
                'status' => $status, 
                'code' => $code,
                'message' => $message,
            ],
            'data' => $data,
        );

        return response()->json($response, $code);
    }
}