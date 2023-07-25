<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Helpers\AccessMenu;
use App\Helpers\ActivityLogUser;
use App\Models\AbilityMenu;
use Alert;
use App\Models\AbilityRole;
use App\Models\User;
use DB;
use DataTables;

class RoleController extends Controller
{
    public function datatableRoles()
    {
        $roles = Role::select('id', 'type', 'unit');
        return DataTables::of($roles)
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function index()
    {
        $dashboard_active = false;
        $parent_menu_active = "Role & Pengguna";
        $child_menu_active = "Role";
        $abilityMenus = AbilityMenu::with('subMenu')->get();
        $menuAccessView = AccessMenu::checkAccessMenu($child_menu_active, 1);
        $menuAccessCreate = AccessMenu::checkAccessMenu($child_menu_active, 2);
        $menuAccessEdit = AccessMenu::checkAccessMenu($child_menu_active, 3);
        $menuAccessDelete = AccessMenu::checkAccessMenu($child_menu_active, 4);
        if (empty($menuAccessView)) {
            return view('pages.errors.403');
        }
        $log_name = 'Menu Role';
        $description = 'Melihat Data List Role';
        ActivityLogUser::insert($log_name, $description);
        return view('pages.roles.index', compact(
            'dashboard_active',
            'parent_menu_active',
            'child_menu_active',
            'abilityMenus',
            'menuAccessCreate',
            'menuAccessEdit',
            'menuAccessDelete',
        ));
    }

    public function createRole(Request $request)
    {
        $dashboard_active = false;
        $parent_menu_active = "Role & Pengguna";
        $child_menu_active = "Role";
        $abilityMenus = AbilityMenu::with('subMenu')
            ->where('parent_id', 0)
            ->get();
        $log_name = 'Menu Role';
        $description = 'Masuk Halaman Tambah Role';
        ActivityLogUser::insert($log_name, $description);
        return view('pages.roles.add', compact(
            'dashboard_active',
            'parent_menu_active',
            'child_menu_active',
            'abilityMenus'
        ));
    }

    public function storeRole(Request $request)
    {
        DB::beginTransaction();
        try {
            $menu = "Role";
            $menuAccessCreate = AccessMenu::checkAccessMenu($menu, 2);
            if (empty($menuAccessCreate)) {
                return view('pages.errors.403');
            }
            $check_exist = Role::where('unit', $request->modalUnitRole)->first();
            if(!empty($check_exist)){
                Alert::error('Gagal', 'Ada duplikat data!');
                return redirect()->back()->withInput();
            }
            $create = new Role();
            $create->type = $request->modalTypeRole;
            $create->unit = $request->modalUnitRole;
            $create->level = ($request->modalUnitRole == "Eselon I") ? 1  
                : (($request->modalUnitRole == "Eselon II") ? 2 
                : (($request->modalUnitRole == "Eselon III") ? 3 : 0));
            $create->save();
            $create->fresh();

            $regex_pattern = '/^\d+-\d+$/';
            $allRequest = $request->all();
            foreach ($allRequest as $menu) {
                $menu_id = explode('-', $menu);
                if(preg_match($regex_pattern, $menu)){
                    $check_menu = AbilityMenu::where('id', $menu_id[0])
                        ->select('id')
                        ->first();
                    if (!empty($check_menu)) {
                        $createMenu = new AbilityRole();
                        $createMenu->role_id = $create->id;
                        $createMenu->ability_id = $menu_id[1];
                        $createMenu->ability_menu_id = $menu_id[0];
                        $createMenu->save();
                    }
                }
            }
            $log_name = 'Menu Role';
            $description = 'Menambahkan Data Role '.$request->modalUnitRole;
            ActivityLogUser::insert($log_name, $description);
            Alert::success('Berhasil', 'Menambahkan role baru');
            DB::commit();
            return redirect()->route('roles');
        } catch (\Throwable $e) {
            return $e->getMessage();
            DB::rollback();
            Alert::error('Gagal', 'Maaf ada kendala server!');
            return redirect()->route('roles');
        }
    }

    public function edit(Request $request, $id)
    {
        $menu = "Role";
        $dashboard_active = false;
        $parent_menu_active = "Role & Pengguna";
        $child_menu_active = "Role";
        $role_edit = Role::where('id', $id)->first();
        $roles = Role::all();
        $is_edit = 1;
        $menuAccessEdit = AccessMenu::checkAccessMenu($menu, 3);
        $abilityMenus = AbilityMenu::with('subMenu')
            ->where('parent_id', 0)
            ->get();
        if (empty($menuAccessEdit)) {
            return view('pages.errors.403');
        }
        
        if (empty($role_edit)) {
            Alert::error('Gagal', 'Maaf tidak ada dengan role tersebut!');
            return redirect()->route('roles');
        }

        $disable_edit = ($role_edit->unit == "Eselon I" 
        || $role_edit->unit == "Eselon II" 
        || $role_edit->unit == "Eselon III") ? true : false;

        $menus = AbilityMenu::where('parent_id', 0)->get();

        $data_menus = [];
        foreach ($menus as $menu) {
            $submenus = AbilityRole::where('role_id', $id)
                ->select('am.id as menu_id', 'parent_id', 'menu', 'ability_id')
                ->join('ability_menus as am', 'am.id', '=', 'ability_roles.ability_menu_id')
                ->where('parent_id', $menu->id)
                ->distinct()
                ->get();
            if(count($submenus) > 1){
                $data_submenus = [];
                foreach ($submenus as $submenu) {
                    $value = strtolower(str_replace(' ', '', $submenu->menu));
                    $data_submenu = array(
                        'menu' => ($submenu->ability_id == 1) ? $value . "View"
                            : (($submenu->ability_id == 2) ? $value . "Create"
                                : (($submenu->ability_id == 3) ? $value . "Edit"
                                    : (($submenu->ability_id == 4) ? $value . "Delete" : ""))),
                    );
    
                    $data_submenus[] = $data_submenu;
                }
                $value = strtolower(str_replace(' ', '', $menu->menu));
                $menu = array(
                    'menu' => $menu->menu,
                    'value' => ($menu->ability_id == 1) ? $value . "View"
                        : (($menu->ability_id == 2) ? $value . "Create"
                            : (($menu->ability_id == 3) ? $value . "Edit"
                                : (($menu->ability_id == 4) ? $value . "Delete" : ""))),
                    'sub_menu' => $data_submenus
                );
            }else{
                $submenus = AbilityRole::where('role_id', $id)
                    ->select('am.id as menu_id', 'parent_id', 'menu', 'ability_id')
                    ->join('ability_menus as am', 'am.id', '=', 'ability_roles.ability_menu_id')
                    ->where('am.id', $menu->id)
                    ->distinct()
                    ->get();
                $data_submenus = [];
                foreach ($submenus as $submenu) {
                    $value = strtolower(str_replace(' ', '', $submenu->menu));
                    $data_submenu = array(
                        'menu' => ($submenu->ability_id == 1) ? $value . "View"
                            : (($submenu->ability_id == 2) ? $value . "Create"
                                : (($submenu->ability_id == 3) ? $value . "Edit"
                                    : (($submenu->ability_id == 4) ? $value . "Delete"
                                        : (($submenu->ability_id == 5) ? $value . "Verify" : "")))),
                    );
    
                    $data_submenus[] = $data_submenu;
                }
                $value = strtolower(str_replace(' ', '', $menu->menu));
                $menu = array(
                    'menu' => $menu->menu,
                    'value' => $data_submenus,
                    'sub_menu' => []
                );
            }
            $data_menus[] = $menu;
        }
        $log_name = 'Menu Role';
        $description = 'Masuk Halaman Edit Role';
        ActivityLogUser::insert($log_name, $description);
        return view('pages.roles.edit', compact(
            'roles',
            'role_edit',
            'menuAccessEdit',
            'parent_menu_active',
            'child_menu_active',
            'dashboard_active',
            'abilityMenus',
            'data_menus',
            'disable_edit',
        ));
    }

    public function update(Request $request, $id)
    {

        DB::beginTransaction();
        try {

            $role = Role::where('id', $id)->first();
            if (empty($role)) {
                Alert::error('Tidak ada Data', 'Maaf tidak ada dengan role tersebut!');
                return redirect()->route('roles');
            }
            if($request->modalUnitRole == "Eselon I" 
            || $request->modalUnitRole == "Eselon II" 
            || $request->modalUnitRole == "Eselon III"){
                Alert::error('Gagal', 'Maaf tidak bisa menggunakan unit role '. $request->modalUnitRole);
                return redirect()->back();
            }
            $role->type = $request->modalTypeRole ?? $role->type;
            $role->unit = $request->modalUnitRole ?? $role->unit;
            $role->save();

            $allRequest = $request->all();

            AbilityRole::where('role_id', $id)->delete();

            $datas = [];
            foreach ($allRequest as $menu) {
                $menu_id = explode('-', $menu);

                if (isset($menu_id[1])) {
                    $check_menu = AbilityMenu::where('id', $menu_id[0])
                        ->select('id')
                        ->first();

                    if (!empty($check_menu)) {
                        $check_exist_access = AbilityRole::where('role_id', $id)
                            ->where('ability_id', $menu_id[1])
                            ->WHERE('ability_menu_id', $menu_id[0])
                            ->select('id')
                            ->get();
                        if (count($check_exist_access) < 1) {
                            $createMenu = new AbilityRole();
                            $createMenu->role_id = $id;
                            $createMenu->ability_id = $menu_id[1];
                            $createMenu->ability_menu_id = $menu_id[0];
                            $createMenu->save();
                        }
                    }
                }
            }
            $log_name = 'Menu Role';
            $description = 'Edit Data Role '.$request->modalUnitRole ?? $role->unit;
            ActivityLogUser::insert($log_name, $description);
            DB::commit();
            Alert::success('Berhasil', 'Berhasil Edit Role');
            return redirect()->route('roles');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Gagal', $e->getMessage());
            return redirect()->route('roles');
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $check_user = User::where('role_id', $id)->get();
            if(count($check_user) > 0){
                return response()->json([
                    'status'    => 'failed', 
                    'code'      => 400, 
                    'message'   => 'Maaf data tidak bisa dihapus!'
                ], 400);
            }
            $role = Role::where('id', $id)->first();
            if (empty($role)) {
                return response()->json([
                    'status'    => 'failed', 
                    'code'      => 400, 
                    'message'   => 'Maaf tidak ada dengan role tersebut!'
                ], 400);
            }
            if($role->unit == "Eselon I" 
            || $role->unit == "Eselon II" 
            || $role->unit == "Eselon III"){
                return response()->json([
                    'status'    => 'failed', 
                    'code'      => 400, 
                    'message'   => 'Maaf tidak bisa menghapus data eselon'
                ], 400);
            }
            $log_name = 'Menu Role';
            $description = 'Menghapus Data Role '.$role->unit;
            ActivityLogUser::insert($log_name, $description);
            $role->delete();
            DB::commit();
            return response()->json([
                'status'    => 'success', 
                'code'      => 200, 
                'message'   => 'Berhasil menghapus data role!'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => 'failed', 
                'code'      => 500, 
                'message'   => 'Maaf ada kendala server!!'
            ], 500);
        }
    }

    public function detailRole($id)
    {
        $role = AbilityRole::where('role_id', $id)
            ->get();
        return $role;
    }
}
