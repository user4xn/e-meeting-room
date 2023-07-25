<?php


use App\Models\AbilityUser;
use App\Models\AbilityMenu;
use App\Models\AbilityRole;
use Illuminate\Support\Facades\DB;

function listMenu()
{

    $menus = AbilityMenu::join('ability_roles as ar', 'ar.ability_menu_id', '=', 'ability_menus.id')
        ->select(DB::raw('DISTINCT ability_menus.parent_id'))
        ->where('role_id', Auth::user()->role_id)
        ->get();

    $menus_single = AbilityMenu::join('ability_roles as ar', 'ar.ability_menu_id', '=', 'ability_menus.id')
        ->select(DB::raw('DISTINCT ability_menu_id'))
        ->where('parent_id', 0)
        ->where('role_id', Auth::user()->role_id)
        ->get();

    $data_menus = [];

    foreach ($menus as $access) {

        $get_menu = AbilityMenu::where('id', $access->parent_id)->first();
        if (empty($get_menu)) {
            foreach ($menus_single as $access) {

                $get_menu = AbilityMenu::where('id', $access->parent_id)->first();
                if (empty($get_menu)) {
                    $get_menu = AbilityMenu::where('id', $access->ability_menu_id)->first();
                }
                $check_child_menu = AbilityRole::join('ability_menus as am', 'am.id', '=', 'ability_roles.ability_menu_id')
                    ->where('parent_id', $access->parent_id)
                    ->where('role_id', Auth::user()->role_id)
                    ->select(DB::raw('DISTINCT parent_id, menu, route, icon'))
                    ->get();

                $menu = array(
                    'id' => $get_menu->id,
                    'menu'  => $get_menu->menu,
                    'route' => $get_menu->route,
                    'icon'  => $get_menu->icon,
                    'sub_menu' => $check_child_menu
                );

                array_push($data_menus, $menu);
            }
        } else {
            $check_child_menu = AbilityRole::join('ability_menus as am', 'am.id', '=', 'ability_roles.ability_menu_id')
                ->where('parent_id', $access->parent_id)
                ->where('role_id', Auth::user()->role_id)
                ->select(DB::raw('DISTINCT parent_id, menu, route, icon'))
                ->get();

            $menu = array(
                'id' => $get_menu->id,
                'menu'  => $get_menu->menu,
                'route' => $get_menu->route,
                'icon'  => $get_menu->icon,
                'sub_menu' => $check_child_menu
            );

            array_push($data_menus, $menu);
        }
    }

    return $data_menus;
}
