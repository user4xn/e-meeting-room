<?php

namespace App\Http\Controllers;

use App\Models\AbilityMenu;
use Illuminate\Http\Request;
use App\Models\AbilityUser;
use App\Helpers\Menu;
use App\Models\AbilityRole;
use App\Models\DocumentType;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogUser;
use DB;
use Session;
use DataTables;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $dashboard_active = true;
        $parent_menu_active = false;
        $child_menu_active = false;

        $log_name = 'Dashboard';
        $description = 'Masuk Halaman Dashboard';
        ActivityLogUser::insert($log_name, $description);

        return view('pages.home.index', compact(
            'dashboard_active', 
            'parent_menu_active', 
            'child_menu_active',
        ));
    }
}
