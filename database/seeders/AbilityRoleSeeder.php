<?php

namespace Database\Seeders;

use App\Models\AbilityRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AbilityUser;
class AbilityRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = array(
            [
                'role_id'   => 1,
                'ability_id'    => 1, 
                'ability_menu_id'   => 2, 
            ],[
                'role_id'   => 1,
                'ability_id'    => 2, 
                'ability_menu_id'   => 2, 
            ],[
                'role_id'   => 1,
                'ability_id'    => 3, 
                'ability_menu_id'   => 2, 
            ],[
                'role_id'   => 1,
                'ability_id'    => 4, 
                'ability_menu_id'   => 2, 
            ],[
                'role_id'   => 1,
                'ability_id'    => 1, 
                'ability_menu_id'   => 3, 
            ],[
                'role_id'   => 1,
                'ability_id'    => 2, 
                'ability_menu_id'   => 3, 
            ],[
                'role_id'   => 1,
                'ability_id'    => 3, 
                'ability_menu_id'   => 3, 
            ],[
                'role_id'   => 1,
                'ability_id'    => 4, 
                'ability_menu_id'   => 3, 
            ],[
                'role_id'   => 1,
                'ability_id'    => 1, 
                'ability_menu_id'   => 5, 
            ],[
                'role_id'   => 1,
                'ability_id'    => 2, 
                'ability_menu_id'   => 5, 
            ],[
                'role_id'   => 1,
                'ability_id'    => 3, 
                'ability_menu_id'   => 5, 
            ],[
                'role_id'   => 1,
                'ability_id'    => 4, 
                'ability_menu_id'   => 5, 
            ]
        );
        foreach($datas as $data) {
            $abilityUser = new AbilityRole();
            $abilityUser->role_id = $data['role_id'];
            $abilityUser->ability_id = $data['ability_id'];
            $abilityUser->ability_menu_id = $data['ability_menu_id'];
            $abilityUser->save();
        }
    }
}
