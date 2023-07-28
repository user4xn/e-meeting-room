<?php

namespace App\Http\Controllers;

use App\Models\MasterRoom;
use Illuminate\Http\Request;
use DataTables;
use DB;
use Alert;
class MasterRoomController extends Controller
{

    public function dataTable()
    {

        $fetch = MasterRoom::all()->toArray();
        $i = 0;
        $reform = array_map(function($new) use (&$i) { 
            $i++;
            return [
                'no' => $i.'.',
                'id' => $new['id'],
                'room_name' => $new['room_name'],
                'room_description' => $new['room_description'],
                'room_location' => $new['room_location'],
                'room_capacity' => $new['room_capacity'],
            ]; 
        }, $fetch);
        return DataTables::of($reform)->make(true);
    }
    public function index()
    {
        $dashboard_active = false;
        $parent_menu_active = "Master Ruangan";
        return view('pages.master_room.index', compact('dashboard_active', 'parent_menu_active'));
    }

    public function updateOrCreate(Request $request)
    {
        try{
            if($request->master_room_id){
                $check_master_room = MasterRoom::where('id', $request->master_room_id)
                    ->first();
                if($check_master_room){
                    $check_master_room->room_name = $request->room_name;
                    $check_master_room->room_description = $request->room_description;
                    $check_master_room->room_location = $request->room_location;
                    $check_master_room->room_capacity = $request->room_capacity;
                    $check_master_room->save();
                    DB::commit();
                    Alert::success('Berhasil', 'Berhasil Mengubah Data Master Room');
                    return redirect()->back();
                }
                Alert::error('Gagal', 'Gagal Menambahkan Data Master Room');
                return redirect()->route('master-room');
            }else{
                $store = new MasterRoom();
                $store->room_name = $request->room_name;
                $store->room_description = $request->room_description;
                $store->room_location = $request->room_location;
                $store->room_capacity = $request->room_capacity;
                $store->save();
                DB::commit();
                Alert::success('Berhasil', 'Berhasil Menambahkan Data Master Room');
                return redirect()->route('master-room');
            }
            
        }catch(\Exception $e){
            DB::rollback();
            Alert::error('Gagal', 'Something Wrong Error.');
            return redirect()->route('master-room');
        }
    }

    public function edit($id)
    {
        $master_room = MasterRoom::find($id);
        return response()->json($master_room);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            MasterRoom::where('id', $id)->delete();
            DB::commit();
            return response()->json([
                'status'    => 'success', 
                'code'      => 200, 
                'message'   => 'Berhasil Menghapus Data Master Room'
            ], 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'status'    => 'failed', 
                'code'      => 500, 
                'message'   => 'Maaf ada kesalahan, silahkan coba lagi!'
            ], 500);
        }
    }
}
