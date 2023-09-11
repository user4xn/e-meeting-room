<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\MasterRoom;
use App\Models\Rent;
use Intervention\Image\Facades\Image;
use Validator;
use Auth;
use DB;
use Storage;
use Carbon\Carbon;

class GuestController extends Controller
{
    public function store(Request $request)
    {
        $date_now = Carbon::now()->format('Y-m-d');
        $datetime_now = Carbon::now()->format('Y-m-d H:i:s');
        $time_now = Carbon::now()->format('H:i:s');

        $validator = Validator::make($request->all(), [
            'rent_id' => 'required|numeric',
            'guest_uuid' => 'required',
            'guest_name' => 'required|string',
            'guest_phone' => 'required|string',
            'guest_position' => 'required|string',
            'work_unit' => 'required|string',
            'signature' => 'required',
        ], [
            'guest_uuid.required' => 'Please input guest uuid.',
            'guest_name.required' => 'Please input guest name.',
            'guest_phone.required' => 'Please input guest phone.',
            'guest_position.required' => 'Please input guest position.',
            'work_unit.required' => 'Please input work unit.',
            'signature.required' => 'Please input a signature image.',
        ]);

        if ($validator->fails()) {
            return ResponseJson::response('failed', 'Error Validation', 422, ['error' => $validator->errors()]);
        }

        $check_rent = Rent::where('id', $request->rent_id)
            ->where(function ($query) use ($datetime_now, $date_now) {
                $query->where('status', 'approved')
                    ->where(function ($query) use ($datetime_now, $date_now) {
                        $query->where(function ($query) use ($date_now, $datetime_now) {
                            $query->where('date_start', '=', $date_now)
                                ->whereRaw("CONCAT(date_start, ' ', time_start) <= ?", $datetime_now);
                        })
                        ->orWhere(function ($query) use ($date_now, $datetime_now) {
                            $query->where('date_start', '<', $date_now)
                                ->where('date_end', '>=', $date_now);
                        });
                    });
            })
            ->select('id', 'room_id', 'guest_count','date_start', 'date_end', 'time_start', 'time_end')
            ->first();

        $check_guest = Guest::Where('uuid', $request->guest_uuid)
            ->where('rent_id', $request->rent_id)
            ->select('id')
            ->first();

        if ($check_guest) {
            return ResponseJson::response('failed', 'Sorry, duplicate guest data', 400, null);
        }

        if (!$check_rent) {
            return ResponseJson::response('failed', 'Sorry, no rental data found', 404, null);
        }
        $room = MasterRoom::where('id', $check_rent->room_id)
            ->first();
        if(!$room){
            return ResponseJson::response('failed', 'Sorry, no master room found', 404, null);
        }
        $total_guest = $check_rent->guest_count + 1;

        if ($total_guest > $room->room_capacity) {
            return ResponseJson::response('failed', 'Sorry, room is full', 400, null);
        }

        DB::beginTransaction();
        try {
            $signatureData = $request->signature;
            $signatureData = strpos($signatureData, 'data:image/png;base64,') === false ? 'data:image/png;base64,' . $signatureData : $signatureData;
            $store = new Guest();
            $store->rent_id = $request->rent_id;
            $store->uuid = $request->guest_uuid;
            $store->name = $request->guest_name;
            $store->phone_number = $request->guest_phone;
            $store->position = $request->guest_position;
            $store->work_unit = $request->work_unit;
            $store->signature = $signatureData;
            $store->save();

            Rent::where('id', $request->rent_id)
                ->update([
                    'guest_count' => $total_guest
                ]);
            DB::commit();
            return ResponseJson::response('success', 'Success storing guest information.', 200, null);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseJson::response('failed', 'Something went wrong.', 500, ['error' => $e->getMessage()]);
        }
    }
}
