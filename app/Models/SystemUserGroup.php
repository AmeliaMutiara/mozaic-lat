<?php
namespace App\Models;
use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SystemUserGroup extends Model
{
    use HasFactory;
    // ** Harus Gunakan Trait Dibawah untuk setiap model
    use SoftDeletes,CreatedUpdatedID;
    protected $table        = 'system_user_group'; 
    protected $primaryKey   = 'user_group_id';
    protected $guarded = [
        'deteted_at',
        'created_at',
        'updated_at',
    ];

    public function menuMapping() {
        return $this->belongsTo(SystemMenuMapping::class,'id_menu','id_menu');
    }

    public function menu() {
        return $this->belongsTo(Model::class,'foreign_key','local_key');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
