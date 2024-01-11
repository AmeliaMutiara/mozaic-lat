<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\CreatedUpdatedID;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    // ** Harus Gunakan Trait Dibawah untuk setiap model
    use SoftDeletes,CreatedUpdatedID;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table        = 'system_user'; 
    protected $primaryKey   = 'user_id';
    protected $fillable = [
        'user_id',
        'username',
        'email',
        'password',
        'user_group_id',
        'division_id',
        'user_token',
        'company_id',
        'merchant_id',
        'phone_number',
        'full_name',
    ];

    public function userGroup() {
        return $this->belongsTo(SystemUserGroup::class,'user_group_id','user_group_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function group() {
        return $this->hasOne(SystemUserGroup::class,'user_group_id','user_group_id');
    }
}
