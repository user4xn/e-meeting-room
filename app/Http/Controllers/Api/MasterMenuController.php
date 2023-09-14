<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterMenu;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
class MasterMenuController extends Controller
{
    public function index()
    {
        $master_menus = MasterMenu::select('id', 'menu_name')
            ->get()
            ->toArray();
        return ResponseJson::response('success', 'Success Get Master Menu.', 200, $master_menus); 
    }

    public function store(Request $request, $user_id)
    {
        return $request;
    }
}
