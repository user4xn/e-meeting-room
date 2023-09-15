<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterMenu;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Models\UserMenuAccess;
use Auth;
class MasterMenuController extends Controller
{
    public function index()
    {
        $master_menus = MasterMenu::select('id', 'menu_name')
            ->get()
            ->toArray();
        return ResponseJson::response('success', 'Success Get Master Menu.', 200, $master_menus); 
    }

    public function getAccessMenu()
    {
        $menu_access = UserMenuAccess::where('user_id', Auth::user()->id)
            ->join('master_menus as mm', 'mm.id', '=', 'user_menu_accesses.master_menu_id')
            ->select('user_menu_accesses.user_id', 'mm.id as master_menu_id', 'mm.menu_name')
            ->get();
        return ResponseJson::response('success', 'Success Get Menu Access.', 200, $menu_access); 
    }
}
