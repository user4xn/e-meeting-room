<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\Guest;
use App\Models\Report;
use App\Models\ReportDetail;
use App\Models\ReportImageRent;
use App\Models\Setting;
use PDF;
use Carbon\Carbon;
use DataTables;

class ReportController extends Controller
{
    public function storeBulkImage(Request $request, $rent_id)
    {
        $check_rent = Rent::where('id', $rent_id)
            ->first();
        if (!$check_rent) {
            return ResponseJson::response('success', 'Data Rent Not Found.', 400, null);
        }
        $rent_name = str_replace(' ', '_', strtolower($check_rent->event_name));
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $rent_name . '_' . time() . '_' . $image->getClientOriginalExtension();
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

    public function listParticipantRentHistory()
    {
        try {
            $fetch = Rent::whereIn('rents.status', ['done'])
                ->join('user_details as ud', 'ud.user_id', '=', 'rents.user_id')
                ->join('users as u', 'u.id', '=', 'rents.user_id')
                ->join('master_rooms as mr', 'mr.id', '=', 'rents.room_id')
                ->select(
                    'rents.id',
                    'u.email as user_email',
                    'ud.name as user_responsible',
                    'ud.phone_number as user_phone',
                    'event_name',
                    'date_start',
                    'date_end',
                    'time_start',
                    'time_end',
                    'rents.status',
                    'room_capacity',
                    'room_name'
                )
                ->get()
                ->toArray();

            $i = 0;
            $reform = array_map(function ($new) use (&$i) {
                $i++;
                $check_file = ReportImageRent::where('rent_id', $new['id'])
                    ->select('id')
                    ->first();
                $total_guest = Guest::where('rent_id', $new['id'])
                    ->count();
                return [
                    'no' => $i . '.',
                    'id' => $new['id'],
                    'user_email' => $new['user_email'],
                    'user_responsible' => $new['user_responsible'],
                    'user_phone' => $new['user_phone'],
                    'event_name' => $new['event_name'],
                    'have_files' => $check_file ? true : false,
                    'date_start' => $new['date_start'],
                    'date_end' => $new['date_end'],
                    'time_start' => $new['time_start'],
                    'time_end' => $new['time_end'],
                    'total_guest' => $total_guest,
                    'status' => $new['status'],
                    'room_name' => $new['room_name'],
                    'room_capacity' => $new['room_capacity']
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
        } catch (\Exception $e) {
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
    }

    public function listParticipantRentOngoing()
    {
        try {
            $fetch = Rent::whereIn('rents.status', ['approved', 'done'])
                ->where(function ($query) {
                    $query->whereDate('date_start', '<=', Carbon::now())
                        ->whereDate('date_end', '>=', Carbon::now());
                })
                ->join('user_details as ud', 'ud.user_id', '=', 'rents.user_id')
                ->join('users as u', 'u.id', '=', 'rents.user_id')
                ->join('master_rooms as mr', 'mr.id', '=', 'rents.room_id')
                ->select(
                    'rents.id',
                    'u.email as user_email',
                    'ud.name as user_responsible',
                    'ud.phone_number as user_phone',
                    'event_name',
                    'date_start',
                    'date_end',
                    'time_start',
                    'time_end',
                    'rents.status',
                    'room_capacity',
                    'room_name'
                )
                ->get()
                ->toArray();

            $i = 0;
            $reform = array_map(function ($new) use (&$i) {
                $i++;
                $check_file = ReportImageRent::where('rent_id', $new['id'])
                    ->select('id')
                    ->first();
                $total_guest = Guest::where('rent_id', $new['id'])
                    ->count();
                return [
                    'no' => $i . '.',
                    'id' => $new['id'],
                    'user_email' => $new['user_email'],
                    'user_responsible' => $new['user_responsible'],
                    'user_phone' => $new['user_phone'],
                    'event_name' => $new['event_name'],
                    'have_files' => $check_file ? true : false,
                    'date_start' => $new['date_start'],
                    'date_end' => $new['date_end'],
                    'time_start' => $new['time_start'],
                    'time_end' => $new['time_end'],
                    'total_guest' => $total_guest,
                    'status' => $new['status'],
                    'room_name' => $new['room_name'],
                    'room_capacity' => $new['room_capacity'],
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
        } catch (\Exception $e) {
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
    }
    public function listRentReport()
    {
        try {
            $fetch = Rent::whereIn('rents.status', ['done'])
                ->join('user_details as ud', 'ud.user_id', '=', 'rents.user_id')
                ->join('users as u', 'u.id', '=', 'rents.user_id')
                ->select(
                    'rents.id',
                    'u.email as user_email',
                    'ud.name as user_responsible',
                    'ud.phone_number as user_phone',
                    'event_name',
                    'date_start',
                    'date_end',
                    'time_start',
                    'time_end',
                    'rents.status'
                )
                ->get()
                ->toArray();

            $i = 0;
            $reform = array_map(function ($new) use (&$i) {
                $i++;
                $check_attachment = Report::where('rent_id', $new['id'])
                    ->join('report_details as rd', 'rd.report_id', '=', 'reports.id')
                    ->select('reports.id')
                    ->first();
                $total_guest = Guest::where('rent_id', $new['id'])
                    ->count();
                return [
                    'no' => $i . '.',
                    'id' => $new['id'],
                    'user_email' => $new['user_email'],
                    'user_responsible' => $new['user_responsible'],
                    'user_phone' => $new['user_phone'],
                    'event_name' => $new['event_name'],
                    'is_completed' => $check_attachment ? true : false,
                    'date_start' => $new['date_start'],
                    'date_end' => $new['date_end'],
                    'time_start' => $new['time_start'],
                    'time_end' => $new['time_end'],
                    'total_guest' => $total_guest,
                    'status' => $new['status'],
                    'event_organization' => ($new['status'] == "approved" || $new['status'] == "done") ? $new['organization'] : ucwords($new['status'])
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
        } catch (\Exception $e) {
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
    }
    public function detailReportRent($rent_id)
    {
        try {
            $check_rent = Rent::where('id', $rent_id)
                ->select('id')
                ->first();
            if (!$check_rent) {
                return ResponseJson::response('success', 'Data Rent Not Found.', 400, null);
            }
            $rent = Rent::where('id', $rent_id)
                ->where('status', 'done')
                ->with('UserResponsible', 'UserVerificator')
                ->first()
                ->toArray();
            $report = Report::where('rent_id', $rent_id)
                ->select('id', 'rent_id', 'date_report')
                ->first();;
            if($report){
                $rent_file = ReportDetail::where('report_id', $report->id)
                    ->select('path', 'type', 'created_at')
                    ->get();
            }else{
                $rent_file = [];
            }

            $rent['report'] = $report;
            $rent['report_file'] = $rent_file;
            $rent['setting'] = Setting::select('office_number', 'address')->first();
            return ResponseJson::response('success', 'Success Get Detail Report Rent.', 200, $rent);
        } catch (\Exception $e) {
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
    }

    public function listGuestByRent($rent_id)
    {
        $check_rent = Rent::join('user_details as ud', 'ud.user_id', '=', 'rents.user_id')
            ->first();
        if (!$check_rent) {
            return ResponseJson::response('failed', 'Data Rent Not Found.', 404, null);
        }
        $guests = Guest::where('rent_id', $rent_id)
            ->select('name', 'phone_number', 'position', 'work_unit', 'signature', 'created_at')
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
        try {
            $fetch = Rent::get()->toArray();

            $i = 0;
            $reform = array_map(function ($new) use (&$i) {
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
        } catch (\Exception $e) {
            return ResponseJson::response('failed', 'Something Wrong Error.', 500, ['error' => $e->getMessage()]);
        }
    }

    public function bulkReportAttachment(Request $request, $rent_id)
    {
       
        $check_rent = Rent::where('id', $rent_id)
            ->first();
        if (!$check_rent) {
            return ResponseJson::response('success', 'Data Rent Not Found.', 400, null);
        }
        $check_report = Report::where('rent_id', $rent_id)
            ->select('id')
            ->first();
        if (!$check_report) {
            return ResponseJson::response('success', 'Data Report Not Found.', 400, null);
        }
        $rent_name = str_replace(' ', '_', strtolower($check_rent->event_name));
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $image) {
                $extension = $image->getClientOriginalExtension();
                $type_file = ($extension == "jpg" || $extension == "png" || $extension == "jpeg" || $extension == "webp") ? "image" : "doc";
                $imageName = $rent_name . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('report/rent', $imageName, 'public');
                $path = '/report/rent/' . $imageName;
                
                $report = new ReportDetail();
                $report->report_id = $check_report->id;
                $report->path = $path;
                $report->type = $type_file;
                $report->save();
                $data[] = $imageName;
            }
        }

        return ResponseJson::response('success', 'Success Report Rent.', 201, null);
    }
}
