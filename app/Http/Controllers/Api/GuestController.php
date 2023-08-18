<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
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
        $time_now = Carbon::now()->format('H:i:s');

        $validator = Validator::make($request->all(), [
            'rent_id' => 'required|numeric',
            'guest_name' => 'required|string',
            'guest_position' => 'required|string',
            'work_unit' => 'required|string',
            'signature' => 'required|image|mimes:jpeg,png|max:2048',
        ], [
            'guest_name.required' => 'Please input guest name.',
            'guest_position.required' => 'Please input guest position.',
            'work_unit.required' => 'Please input work unit.',
            'signature.required' => 'Please input a signature image.',
        ]);

        if ($validator->fails()) {
            return ResponseJson::response('failed', 'Error Validation', 422, ['error' => $validator->errors()]);
        }

        $check_rent = Rent::where('id', $request->rent_id)
            ->where('status', 'approved')
            ->select('id', 'date_start', 'date_end', 'time_start', 'time_end')
            ->first();

        $check_guest = Guest::where('name', $request->guest_name)
            ->where('rent_id', $request->rent_id)
            ->select('id')
            ->first();

        if ($check_guest) {
            return ResponseJson::response('failed', 'Sorry, duplicate guest data', 400, null);
        }

        if (!$check_rent) {
            return ResponseJson::response('failed', 'Sorry, no rental data found', 404, null);
        }

        $check_total_guest = Guest::where('rent_id', $request->rent_id)
            ->select('id')
            ->get();
        $total_guest = count($check_total_guest) + 1;

        if ($total_guest > $check_rent->guest_count) {
            return ResponseJson::response('failed', 'Sorry, room is full', 400, null);
        }

        DB::beginTransaction();
        try {
            if (($date_now >= $check_rent->date_start && $date_now <= $check_rent->date_end) &&
                ($time_now >= $check_rent->time_start && $time_now <= $check_rent->time_end)
            ) {
                $image = $request->file('signature');
                $extension = $image->getClientOriginalExtension();
                $filename = $request->guest_name . '_signature_' . uniqid() . '.' . $extension;
                $image_path = $image->storeAs('signature', $filename, 'public');

                $store = new Guest();
                $store->rent_id = $request->rent_id;
                $store->name = $request->guest_name;
                $store->position = $request->guest_position;
                $store->work_unit = $request->work_unit;
                $store->signature = $image_path;
                $store->save();

                DB::commit();
                return ResponseJson::response('success', 'Success storing guest information.', 200, null);
            } else {
                return ResponseJson::response('failed', 'Sorry, rental has expired.', 400, null);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseJson::response('failed', 'Something went wrong.', 500, ['error' => $e->getMessage()]);
        }
    }
}
