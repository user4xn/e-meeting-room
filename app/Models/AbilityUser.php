<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class AbilityUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 
        'ability_id',
        'ability_menu_id',
    ];
    public function subMenu()
    {
        return $this->hasMany('App\Models\AbilityMenu', 'parent_id', 'id')
            ->select('ability_menus.id as id', 'user_id','ability_menu_id', 'ability_menus.id as menu_id', 'parent_id', 'menu')
            ->join('ability_users as au', 'au.ability_menu_id', '=', 'ability_menus.id')
            ->where('user_id', Auth::user()->id)
            ->where('ability_menus.parent_id', '!=', 0)
            ->distinct();
    }
}
