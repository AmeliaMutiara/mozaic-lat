<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// * Menggunakan nama 'ParentTable' Karena nama 'Parent' saja tidak boleh
class ParentTable extends Model
{
    use HasFactory;
    // ** Harus Gunakan Trait Dibawah untuk setiap model
    use SoftDeletes, CreatedUpdatedID;
    protected $table        = 'parent_table';
    protected $primaryKey   = 'parent_id';

    protected $guarded = [
        'deteted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];
    public function child() {
        return $this->hasMany(Child::class,'parent_id','parent_id');
    }
}
