<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\Guest;
use App\Models\ReportImageRent;
use PDF;
use Carbon\Carbon;
use DataTables;
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

    public function listReportRentHistory()
    {
        try{
            $fetch = Rent::whereIn('status', ['approved', 'done'])
                ->get()
                ->toArray();

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
            $datatables =  DataTables::of($reform)->make(true);
            $data = array(
                'draw' => $datatables->original['draw'],
                'recordsTotal' => $datatables->original['recordsTotal'],
                'recordsFiltered' => $datatables->original['recordsFiltered'],
                'data' => $datatables->original['data']
            );
            return ResponseJson::response('success', 'Success Get List Room.', 200, $data); 

        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }

    public function listReportRentOngoing()
    {
        try{
            $fetch = Rent::whereIn('status', ['approved', 'done'])
                ->whereDate('date_start', Carbon::now()->format('Y-m-d'))
                ->get()
                ->toArray();

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
            $datatables =  DataTables::of($reform)->make(true);
            $data = array(
                'draw' => $datatables->original['draw'],
                'recordsTotal' => $datatables->original['recordsTotal'],
                'recordsFiltered' => $datatables->original['recordsFiltered'],
                'data' => $datatables->original['data']
            );
            return ResponseJson::response('success', 'Success Get List Room.', 200, $data); 

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

    public function listGuestByRent($rent_id)
    {
        $check_rent = Rent::join('user_details as ud', 'ud.user_id', '=', 'rents.user_id')
            ->first();
        if(!$check_rent){
            return ResponseJson::response('failed', 'Data Rent Not Found.', 404, null); 
        }
        $guests = Guest::where('rent_id', $rent_id)
            ->select('name', 'signature')
            ->get()
            ->toArray();
        $data = array(
            "user_responsible" => $check_rent->name,
            "nip_responsible" => $check_rent->nip,
            'list_guests' => $guests,
        );

        return ResponseJson::response('success', 'Success Get List Guest.', 200, $data); 
    }

    public function listReportRentPdf()
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
                    'no' => $i,
                    'event_name' => $new['event_name'],
                    'have_files' => $check_file ? true : false,
                    'date_start' => $new['date_start'],
                    'date_end' => $new['date_end'],
                    'time_start' => Carbon::parse($new['time_start'])->format('H:i'),
                    'time_end' => Carbon::parse($new['time_end'])->format('H:i'),
                    'total_guest' => $total_guest,
                    'status' => $new['status']
                ]; 
            }, $fetch);
            $data = array(
                'list_rents' => $reform
            );
            $pdf = PDF::loadView('pdf.report_rent_list', $data);
            return $pdf->stream();
        }catch(\Exception $e){
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]); 
        }
    }
}
