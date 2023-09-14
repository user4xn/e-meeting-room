<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }    

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    public function menuAccess()
    {
        return $this->hasMany(UserMenuAccess::class, 'user_id')
            ->join('master_menus as mm', 'mm.id', '=', 'user_menu_accesses.master_menu_id')
            ->select('user_menu_accesses.user_id', 'mm.id as master_menu_id', 'mm.menu_name');
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }
    public function getEmailVerifiedAtAttribute($value)
    {
        if($value != null){
            return date('Y-m-d H:i:s', strtotime($value));
        }else{
            return null;
        }
    }
}