<?php

namespace App\Helpers;
use App\Models\AbilityUser;
use App\Models\AbilityMenu;
use App\Models\AbilityRole;
use Auth;
use DB;
class AccessMenu
{

    public static function checkAccessMenu($menu, $access)
    {
        $menuAccess = AbilityRole::where('role_id', Auth::user()->role_id)
            ->select('am.id as menu_id', 'parent_id', 'ability_id', 'menu')
            ->where('am.menu', 'LIKE', '%' . $menu . '%')
            ->where('ability_id', $access)
            ->join('ability_menus as am', 'am.id', '=', 'ability_roles.ability_menu_id')
            ->first();

        return $menuAccess;
    }

    public static function createAccessMenu($arrayAccess, $user_id)
    {   
        DB::beginTransaction();
        try{
            foreach ($arrayAccess as $access) {

                if ($access['parent_id'] != 0) {

                    $check_parent = AbilityMenu::where('id', $access['parent_id'])->first();
                    if (!empty($check_parent)) {

                        $check_parent_menu = AbilityUser::where('ability_menu_id', $access['parent_id'])
                            ->where('user_id', $user_id)
                            ->first();
                        if (empty($check_parent_menu)) {

                            if(!empty($access['access']['view'])
                            || !empty($access['access']['create'])
                            || !empty($access['access']['edit'])
                            || !empty($access['access']['delete'])){
                                AbilityUser::create([
                                    'user_id'   => $user_id,
                                    'ability_id'    => 1,
                                    'ability_menu_id'   => $access['parent_id'],
                                ]);
                            }

                            if (!empty($access['access']['view'])) {
                                AbilityUser::create([
                                    'user_id'   => $user_id,
                                    'ability_id'    => 1,
                                    'ability_menu_id'   => $access['id'],
                                ]);
                            }

                            if (!empty($access['access']['create'])) {
                                AbilityUser::create([
                                    'user_id'   => $user_id,
                                    'ability_id'    => 2,
                                    'ability_menu_id'   => $access['id'],
                                ]);
                            }

                            if (!empty($access['access']['edit'])) {
                                AbilityUser::create([
                                    'user_id'   => $user_id,
                                    'ability_id'    => 3,
                                    'ability_menu_id'   => $access['id'],
                                ]);
                            }

                            if (!empty($access['access']['delete'])) {
                                AbilityUser::create([
                                    'user_id'   => $user_id,
                                    'ability_id'    => 4,
                                    'ability_menu_id'   => $access['id'],
                                ]);
                            }

                            if (!empty($access['access']['verify'])) {
                                AbilityUser::create([
                                    'user_id'   => $user_id,
                                    'ability_id'    => 5,
                                    'ability_menu_id'   => $access['id'],
                                ]);
                            }
                        } else {

                            if (!empty($access['access']['view'])) {
                                AbilityUser::create([
                                    'user_id'   => $user_id,
                                    'ability_id'    => 1,
                                    'ability_menu_id'   => $access['id'],
                                ]);
                            }

                            if (!empty($access['access']['create'])) {
                                AbilityUser::create([
                                    'user_id'   => $user_id,
                                    'ability_id'    => 2,
                                    'ability_menu_id'   => $access['id'],
                                ]);
                            }

                            if (!empty($access['access']['edit'])) {
                                AbilityUser::create([
                                    'user_id'   => $user_id,
                                    'ability_id'    => 3,
                                    'ability_menu_id'   => $access['id'],
                                ]);
                            }

                            if (!empty($access['access']['delete'])) {
                                AbilityUser::create([
                                    'user_id'   => $user_id,
                                    'ability_id'    => 4,
                                    'ability_menu_id'   => $access['id'],
                                ]);
                            }

                            if (!empty($access['access']['verify'])) {
                                AbilityUser::create([
                                    'user_id'   => $user_id,
                                    'ability_id'    => 5,
                                    'ability_menu_id'   => $access['id'],
                                ]);
                            }
                        }
                    } else {
                        if (!empty($access['access']['view'])) {
                            AbilityUser::create([
                                'user_id'   => $user_id,
                                'ability_id'    => 1,
                                'ability_menu_id'   => $access['id'],
                            ]);
                        }

                        if (!empty($access['access']['create'])) {
                            AbilityUser::create([
                                'user_id'   => $user_id,
                                'ability_id'    => 2,
                                'ability_menu_id'   => $access['id'],
                            ]);
                        }

                        if (!empty($access['access']['edit'])) {
                            AbilityUser::create([
                                'user_id'   => $user_id,
                                'ability_id'    => 3,
                                'ability_menu_id'   => $access['id'],
                            ]);
                        }

                        if (!empty($access['access']['delete'])) {
                            AbilityUser::create([
                                'user_id'   => $user_id,
                                'ability_id'    => 4,
                                'ability_menu_id'   => $access['id'],
                            ]);
                        }

                        if (!empty($access['access']['verify'])) {
                            AbilityUser::create([
                                'user_id'   => $user_id,
                                'ability_id'    => 5,
                                'ability_menu_id'   => $access['id'],
                            ]);
                        }
                    }
                }
            }
            DB::commit();
        }catch(\Throwable $e){
            DB::rollback();
        }
    }
}