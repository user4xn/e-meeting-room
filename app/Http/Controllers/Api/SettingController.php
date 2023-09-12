<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\Setting;

class SettingController extends Controller
{
    public function setting(Request $request)
    {
        try{

            $check_setting = Setting::first();
            if($check_setting){
                $check_setting->address = $request->address;
                $check_setting->office_number = $request->office_number;
                $check_setting->save();
            }else{
                $store = new Setting();
                $store->address = $request->address;
                $store->office_number = $request->office_number;
                $store->save();
            }
            return ResponseJson::response('success', 'Success Setting Web.', 200, null);
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Internal Server Error.', 500, ['error' => $e->getMessage()]);
        }
    }
}
