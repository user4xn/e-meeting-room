<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class AbilityMenu extends Model
{
    use HasFactory;
    
    public function accessSubMenu()
    {
        return $this->hasMany('App\Models\AbilityMenu', 'parent_id', 'parent_id')
            ->select('id as child_id','parent_id', 'menu', 'route', 'icon')
            ->join('ability_roles', 'ability_roles.ability_menu_id', '=', 'ability_menus.id')
            ->where('ability_roles.role_id', Auth::user()->role_id);
    }
    public function subMenu()
    {
        return $this->hasMany('App\Models\AbilityMenu', 'parent_id', 'id');
    }
}
