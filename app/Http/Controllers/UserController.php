<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use ErrorException;
use Alert;
use App\Models\AbilityRole;
use App\Helpers\AccessMenu;
use App\Helpers\ActivityLogUser;
use App\Models\AbilityMenu;
use App\Models\NotificationUser;
use App\Models\User;
use App\Models\LogLogin;
use App\Mail\EmailVerification;
use DB;
use Auth;
use Hash;
use Mail;
use Validator;
use DataTables;

class UserController extends Controller
{

    public function getData()
    {
        $users = User::select('users.id', 'full_name', 'email', 'nip', 'phone_number', 'unit')
            ->orderBy('users.created_at', 'desc')
            ->leftjoin('roles', 'roles.id', '=', 'users.role_id');
            
        return DataTables::of($users)
            ->rawColumns(['action'])
            ->make(true);
    }

    public function allUsers()
    {
        $menu = "Data Pengguna";
        $dashboard_active = false;
        $parent_menu_active = "Role & Pengguna";
        $child_menu_active = "Data Pengguna";
        $menuAccessView = AccessMenu::checkAccessMenu($menu, 1);
        $menuAccessCreate = AccessMenu::checkAccessMenu($menu, 2);
        $menuAccessEdit = AccessMenu::checkAccessMenu($menu, 3);
        $menuAccessDelete = AccessMenu::checkAccessMenu($menu, 4);

        if (empty($menuAccessView)) {
            return view('pages.errors.403');
        }
        $log_name = 'Menu Pengguna';
        $description = 'Melihat Data List User';
        ActivityLogUser::insert($log_name, $description);
        $users = User::select('id', 'full_name', 'email', 'nip', 'phone_number')->get();
        return view('pages.users.index', compact(
            'users',
            'menuAccessCreate',
            'menuAccessDelete',
            'menuAccessView',
            'menuAccessEdit',
            'parent_menu_active',
            'child_menu_active',
            'dashboard_active'
        ));
    }

    public function addUser(Request $request)
    {
        $menu = "Data Pengguna";
        $dashboard_active = false;
        $parent_menu_active = "Role & Pengguna";
        $child_menu_active = "Data Pengguna";
        $menuAccessCreate = AccessMenu::checkAccessMenu($menu, 2);
        if (empty($menuAccessCreate)) {
            return view('pages.errors.403');
        }
        $abilityMenus = AbilityMenu::with('subMenu')
            ->where('parent_id', 0)
            ->get();
        $roles = Role::all();
        $menus = AbilityMenu::where('parent_id', 0)
            ->with('subMenu')
            ->get();
        $role_menus = [];
        foreach ($roles as $rl){
            $data_menus = [
                'role_id' => $rl->id,
                'role_unit' => $rl->unit,
                'role_type' => $rl->type,
                'data_menu' => [],
            ];
            foreach ($menus as $menu) {
                $submenus = AbilityRole::where('role_id', $rl->id)
                    ->select('role_id', 'am.id as menu_id', 'parent_id', 'menu', 'ability_id')
                    ->join('ability_menus as am', 'am.id', '=', 'ability_roles.ability_menu_id')
                    ->where('parent_id', $menu->id)
                    ->distinct()
                    ->get();
                $data_submenus = [];
                if(count($submenus) > 0){
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
                    $data_menus['data_menu'][] = $menu;
                }else{
                    if(count($menu->subMenu) < 1){
                        $access_menu = AbilityRole::where('ability_menu_id', $menu->id)
                            ->where('role_id', $rl->id)
                            ->get();
                        $values = [];
                        foreach ($access_menu as $am) {
                            $value = strtolower(str_replace(' ', '', $menu->menu));
                            $access_value = array(
                                'menu' => ($am->ability_id == 1) ? $value . "View"
                                    : (($am->ability_id == 2) ? $value . "Create"
                                        : (($am->ability_id == 3) ? $value . "Edit"
                                            : (($am->ability_id == 4) ? $value . "Delete"
                                             : (($am->ability_id == 5) ? $value . "Verify" : "")))),
                            );
                            if(count($values) == count($access_menu)){
                                break;
                            }
                            array_push($values,$access_value);
                        }
                        $menu = array(
                            'menu' => $menu->menu,
                            'value' => array_values(array_unique($values, SORT_REGULAR)),
                            'sub_menu' => []
                        );
                        $data_menus['data_menu'][] = $menu;
                    }
                }
               
            }
            
            $role_menus[] = $data_menus;
        }
        $log_name = 'Menu Pengguna';
        $description = 'Masuk Halaman Tambah User';
        ActivityLogUser::insert($log_name, $description);
        return view('pages.users.add', compact(
            'role_menus',
            'abilityMenus',
            'parent_menu_active',
            'child_menu_active',
            'dashboard_active'
        ));
    }

    public function storeUser(Request $request)
    {
        $menu = "Data Pengguna";
        $menuAccessCreate = AccessMenu::checkAccessMenu($menu, 2);
        
        if (empty($menuAccessCreate)) {
            return view('pages.errors.403');
        }

        $arrayAccess = $this->arrayAccess($request);
        $validate = Validator::make($request->all(), [
            'email' => 'required|unique:users',
            'fullName' => 'required',
            'newPassword' => 'required',
            'nip' => 'required',
            'noSk' => 'required',
            'selectRole' => 'required',
        ], [
            'fullName.required' => 'Maaf kamu belum input nama lengkap',
            'email.required' => 'Maaf kamu belum input email',
            'email.unique' => 'Maaf email kamu masukan sudah di gunakan!',
            'newPassword.required' => 'Maaf kamu belum input password',
            'nip.required' => 'Maaf kamu belum input NIP',
            'noSk.required' => 'Maaf kamu belum input nomor SK',
            'selectRole.required' => 'Maaf kamu belum pilih Role',
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate->errors())->withInput();
        }

        DB::beginTransaction();
        try {
            $create = new User();
            $create->full_name = $request->fullName;
            $create->email = $request->email;
            $create->password = bcrypt($request->newPassword);
            $create->nip = $request->nip;
            $create->no_sk = $request->noSk;
            $create->phone_number = $request->phoneNumber;
            $create->role_id = $request->selectRole;
            $create->save();
            $user_id = $create->id;
            AccessMenu::createAccessMenu($arrayAccess, $user_id);
            
            // Mail::to($create->email)->send(new EmailVerification($user_id));

            $log_name = 'Menu Pengguna';
            $description = 'Berhasil Menambahkan User '.$request->fullName;
            ActivityLogUser::insert($log_name, $description);

            DB::commit();
            Alert::success('Berhasil', 'Menambah user Baru');
            return redirect()->route('users');
        } catch (\Exception $e) {
            return $e->getMessage();
            DB::rollback();
            Alert::error('Gagal', 'Maaf gagal menambahkan data');
            return back();
        }
    }

    public function settingProfile()
    {
        $user_data = Auth::user();
        $parent_menu_active = false;
        $child_menu_active = false;
        $dashboard_active = false;
        
        $login_logs = LogLogin::where('user_id', $user_data->id)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();
        $log_name = 'Menu Pengguna';
        $description = 'Masuk Halaman Pengaturan Profile';
        ActivityLogUser::insert($log_name, $description);

        return view('pages.profile.index', compact(
            'user_data',
            'login_logs',
            'parent_menu_active',
            'child_menu_active',
            'dashboard_active',
        ));
    }

    public function delete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $menu = "Data Pengguna";
            $menuAccessCreate = AccessMenu::checkAccessMenu($menu, 4);
            if (empty($menuAccessCreate)) {
                return view('pages.errors.403');
            }
            $checkUser = User::where('id', $id)->first();
            if(empty($checkUser)){
                return response()->json([
                    'status'    => 'failed', 
                    'code'      => 400,
                    'message'   => 'Maaf tidak ada data user tersebut!'
                ], 400);
            }

            $log_name = 'Menu Pengguna';
            $description = 'Menghapus Data Pengguna '.$checkUser->full_name;
            ActivityLogUser::insert($log_name, $description);

            User::where('id', $id)->delete();

            DB::commit();
            return response()->json([
                'status'    => 'failed', 
                'code'      => 200,
                'message'   => 'Berhasil menghapus data pengguna'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'    => 'failed', 
                'code'      => 500,
                'message'   => 'Maaf ada kendala server!'
            ], 500);
        }
    }

    public function detail(Request $request, $id)
    {
        $menu = "Data Pengguna";
        $dashboard_active = false;
        $parent_menu_active = "Role & Pengguna";
        $child_menu_active = "Data Pengguna";
        $menuAccessView = AccessMenu::checkAccessMenu($menu, 1);
        $menuAccessEdit = AccessMenu::checkAccessMenu($menu, 3);
        $menuAccessDelete = AccessMenu::checkAccessMenu($menu, 4);

        if (empty($menuAccessEdit)) {
            return view('pages.errors.403');
        }

        $user = User::where('id', $id)->first();
        
        $menus = AbilityMenu::where('parent_id', 0)->get();

        $abilityMenus = AbilityMenu::with('subMenu')->where('parent_id', 0)->get();
        $abilityRole = AbilityRole::where('role_id', $user->role_id)->get()->toArray();
        $roles = Role::all();
            
        $data_menus = [];
        foreach ($menus as $menu) {
            $submenus = AbilityRole::where('role_id', $user->role_id)
                ->select('am.id as menu_id', 'parent_id', 'menu', 'ability_id')
                ->join('ability_menus as am', 'am.id', '=', 'ability_roles.ability_menu_id')
                ->where('parent_id', $menu->id)
                ->distinct()
                ->get();
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
            $data_menus[] = $menu;
        }
        $log_name = 'Menu Pengguna';
        $description = 'Masuk Detail Pengguna '.$user->full_name;
        ActivityLogUser::insert($log_name, $description);
        return view('pages.users.detail', compact(
            'user',
            'abilityMenus',
            'abilityRole',
            'roles',
            'menuAccessEdit',
            'menuAccessDelete',
            'data_menus',
            'parent_menu_active',
            'child_menu_active',
            'dashboard_active'
        ));
    }

    public function update(Request $request, $id)
    {
        $menu = "Data Pengguna";
        $menuAccessEdit = AccessMenu::checkAccessMenu($menu, 4);

        if (empty($menuAccessEdit)) {
            return view('pages.errors.403');
        }

        $messages = [
            'phoneNumber.unique' => 'Nomor telepon telah digunakan',
            'nip.unique' => 'NIP telah digunakan',
            'email.unique' => 'Email telah digunakan',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'unique:users,email,' . $id,
            'nip' => 'unique:users,nip,' . $id,
            'phoneNumber' => 'unique:users,phone_number,' . $id,
        ], $messages);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $err) {
                $errors[] = $err;
            }
            return redirect()->route('detail-user', $id)->withErrors(implode(', ', $errors))->withInput();
        }

        $check_user = User::where('id', $id)->first();
        if(!$check_user){
            Alert::error('Gagal', 'Maaf tidak ada data user');
            return redirect()->route('detail-user', $id)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            if ($request->newPassword) {
                User::where('id', $id)->update([
                    'full_name' => $request->fullName,
                    'email' => $request->email,
                    'phone_number' => $request->phoneNumber,
                    'role_id' => $request->selectRole, 
                    'satker_id' => $request->selectSatker ?? 0, 
                    'nip' => $request->nip,
                    'no_sk' => $request->noSk,
                    'status' => $request->statusUser,
                    'password' => bcrypt($request->newPassword),
                    'non_active_at' => (!empty($request->statusUser) && $check_user->non_active_at == null) ? date("Y-m-d H:i:s") : null
                ]);
            } else {
                User::where('id', $id)->update([
                    'full_name' => $request->fullName,
                    'email' => $request->email,
                    'phone_number' => $request->phoneNumber,
                    'role_id' => $request->selectRole,
                    'satker_id' => $request->selectSatker ?? 0, 
                    'nip' => $request->nip,
                    'no_sk' => $request->noSk,
                    'status' => $request->statusUser,
                    'non_active_at' => (!empty($request->statusUser) && $check_user->non_active_at == null) ? date("Y-m-d H:i:s") : null
                ]);
            }

            $log_name = 'Menu Pengguna';
            $description = 'Update data pengguna '.$request->full_name;
            ActivityLogUser::insert($log_name, $description);
            DB::commit();
            Alert::success('Berhasil', 'Mengubah data user!');
            return redirect()->route('detail-user', $id);
        } catch (\Exception $e) {
            return $e->getMessage();
            DB::rollback();
            Alert::error('Gagal', 'Maaf ada kesalahan, silahkan coba lagi');
            return redirect()->route('detail-user', $id);
        }
    }

    private function arrayAccess($request)
    {
        $role = array(
            'id'    => 2,
            'parent_id' => 1,
            'access'    => [
                'view'  => $request->roleView,
                'create'  => $request->roleCreate,
                'edit'  => $request->roleEdit,
                'delete'  => $request->roleDelete,
            ],
        );

        $datapengguna = array(
            'id'    => 3,
            'parent_id' => 1,
            'access'    => [
                'view'  => $request->datapenggunaView,
                'create'  => $request->datapenggunaCreate,
                'edit'  => $request->datapenggunaEdit,
                'delete'  => $request->datapenggunaDelete,
            ],
        );

        $final = array($role, $datapengguna);

        return $final;
    }

    public function updateProfile(Request $request)
    {
        $messages = [
            'phoneNumber.unique' => 'Nomor telepon telah digunakan',
            'nip.unique' => 'NIP telah digunakan',
            'email.unique' => 'Email telah digunakan',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'unique:users,email,' . Auth::user()->id,
            'nip' => 'unique:users,nip,' . Auth::user()->id,
            'phoneNumber' => 'unique:users,phone_number,' . Auth::user()->id,
        ], $messages);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $err) {
                $errors[] = $err;
            }
            return redirect()->route('setting-profile')->withErrors(implode(', ', $errors))->withInput();
        }

        DB::beginTransaction();
        try {

            $user = Auth::user();
            $update = User::where('id', $user->id)->update([
                'full_name' => $request->fullName,
                'email' => $request->email,
                'phone_number' => $request->phoneNumber,
                'nip' => $request->nip,
            ]);

            $log_name = 'Menu Pengguna';
            $description = 'Update data profile pengguna '.$request->fullName;
            ActivityLogUser::insert($log_name, $description);

            DB::commit();
            Alert::success('Berhasil', 'Mengubah profile!');
            return redirect()->route('setting-profile');
        } catch (\Exception $e) {

            DB::rollback();
            Alert::error('Gagal', 'Maaf ada kesalahan, silahkan coba lagi');
            return redirect()->route('setting-profile');
        }
    }

    public function updateUserPassword(Request $request)
    {
        DB::beginTransaction();
        try {

            $user = Auth::user();
            $checkCurrent = Hash::check($request->currentPassword, $user->password, []);
            if (!$checkCurrent) {
                Alert::error('Gagal', 'Sandi terkini tidak valid');
                return redirect()->route('setting-profile');
            }

            $update = User::where('id', $user->id)->update([
                'password' => bcrypt($request->newPassword),
                'last_password_changed' => date('Y-m-d H:i:s'),
            ]);

            $log_name = 'Menu Pengguna';
            $description = 'Update Password Pengguna '.$user->full_name;
            ActivityLogUser::insert($log_name, $description);

            DB::commit();
            Alert::success('Berhasil', 'Mengubah sandi!');
            return redirect()->route('setting-profile');
        } catch (\Exception $e) {

            DB::rollback();
            Alert::error('Gagal', 'Maaf ada kesalahan, silahkan coba lagi');
            return redirect()->route('setting-profile');
        }
    }

    public function readAllMessage(Request $request)
    {
        DB::beginTransaction();
        try {
            $notificationUser = NotificationUser::where('user_id', Auth::user()->id)->update([
                'is_read' => 1
            ]);

            if($notificationUser) {
                $log_name = 'Menu Pengguna';
                $description = 'Membaca Semua Notifikasi Pengguna '.Auth::user()->full_name;
                ActivityLogUser::insert($log_name, $description);
                
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'success mark all read'
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => 'failed',
                'code' => 400,
                'message' => $th->getMessage()
            ]);
        }
    }
}
