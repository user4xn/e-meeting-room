<?php

namespace Database\Seeders;

use App\Models\Ability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AbilityMenu;
class AbilityMenuSeeder extends Seeder
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
                'id'    => 1, 
                'parent_id' => 0, 
                'menu'  => 'Role & Pengguna', 
                'route' => '', 
                'icon'  => 'ti ti-settings'
            ],[
                'id'    => 2, 
                'parent_id' => 1, 
                'menu'  => 'Role',
                'route' => 'roles',
                'icon'  => ''
            ],[
                'id'    => 3, 
                'parent_id' => 1, 
                'menu'  => 'Data Pengguna',
                'route' => 'users', 
                'icon'  => ''
            ],[
                'id'    => 4, 
                'parent_id' => 0, 
                'menu'  => 'Master',
                'route' => '',
                'icon'  => 'ti ti-category-2'
            ],[
                'id'    => 5, 
                'parent_id' => 4, 
                'menu'  => 'Satker',
                'route' => 'satker', 
                'icon'  => ''
            ]
        );

        foreach($datas as $data) {
            $abilityMenu = new AbilityMenu();
            $abilityMenu->id = $data['id'];
            $abilityMenu->parent_id = $data['parent_id'];
            $abilityMenu->menu = $data['menu'];
            $abilityMenu->route = $data['route'];
            $abilityMenu->icon = $data['icon'];
            $abilityMenu->save();
        }
    }
}
