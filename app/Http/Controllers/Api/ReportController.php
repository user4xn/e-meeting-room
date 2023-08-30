<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\Guest;
use App\Models\ReportImageRent;

class ReportController extends Controller
{
    public function storeBulkImage(Request $request, $rent_id)
    {
        $check_rent = Rent::where('id', $rent_id)
            ->first();
        if(!$check_rent){
            return ResponseJson::response('success', 'Data Rent Not Found.', 400, null); 
        }
        $rent_name = str_replace(' ', '_', strtolower($check_rent->event_name));
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $rent_name.'_'.time() . '_' . $image->getClientOriginalExtension();
                $image->storeAs('report/rent', $imageName, 'public');
                $path = '/report/rent/' . $imageName;
                
                $report = new ReportImageRent();
                $report->rent_id = $rent_id;
                $report->file_path = $path;
                $report->save();
                
            }
        }

        return ResponseJson::response('success', 'Success Report Rent.', 201, null); 
    }

    public function listReportRent()
    {
        try{
            $fetch = Rent::get()->toArray();

            $i = 0;
            $reform = array_map(function($new) use (&$i) { 
                $i++;
                $check_file = ReportImageRent::where('rent_id', $new['id'])
                    ->select('id')
                    ->first();
                $total_guest = Guest::where('rent_id', $new['id'])
                    ->count();
                return [
                    'id' => $new['id'],
                    'event_name' => $new['event_name'],
                    'have_files' => $check_file ? true : false,
                    'date_start' => $new['date_start'],
                    'date_end' => $new['date_end'],
                    'time_start' => $new['time_start'],
                    'time_end' => $new['time_end'],
                    'total_guest' => $total_guest,
                    'status' => $new['status']
                ]; 
            }, $fetch);
            return ResponseJson::response('success', 'Success Get List Calendar.', 200, $reform); 

        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function detailReportRent($rent_id)
    {
        try{
            $rent = Rent::where('id', $rent_id)
                ->first()
                ->toArray();
            if(!$rent){
                return ResponseJson::response('success', 'Data Rent Not Found.', 400, null); 
            }
            $rent_file = ReportImageRent::where('rent_id', $rent_id)
                ->get()
                ->pluck('file_path')
                ->toArray();
                
            $rent['data_files'] = $rent_file;
            $rent['total_guest'] = Guest::where('rent_id', $rent_id)
                ->select('id')
                ->count();
            return ResponseJson::response('success', 'Success Get List Calendar.', 200, $rent); 

        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function exportFilePdf(Request $request, $rent_id)
    {
        try{

        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }
}
