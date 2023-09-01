<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Rent extends Model
{
    use HasFactory, SoftDeletes;

    public function Room()
    {
        return $this->hasOne('App\Models\MasterRoom', 'id', 'room_id');
    }
    
    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }
  
    public function getUpdatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }
}
