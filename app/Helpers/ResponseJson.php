<?php

namespace App\Helpers;
use App\Models\ActivityLog;
use Auth;
use DB;
class ResponseJson
{
    public static function response($message, $status, $code, $data)
    {
        $response = array(
            'meta' => [
                'message' => $message,
                'code' => $code,
                'status' => $status, 
            ],
            'data' => $data,
        );

        return response()->json($response, $code);
    }
}