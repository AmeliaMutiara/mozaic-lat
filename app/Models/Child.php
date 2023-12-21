<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Child extends Model
{
    use HasFactory;
    // ** Harus Gunakan Trait Dibawah untuk setiap model
    use SoftDeletes, CreatedUpdatedID;
    protected $table        = 'child_table';
    protected $primaryKey   = 'child_id';

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
}
