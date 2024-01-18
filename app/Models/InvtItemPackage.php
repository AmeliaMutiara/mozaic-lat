<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvtItemPackage extends Model
{
    use HasFactory;
    use SoftDeletes, CreatedUpdatedID;

    protected $table        = 'invt_item_package';
    protected $primaryKey   = 'invt_package_id';
    protected $guarded = [
        'updated_at',
        'created_at'
    ];

}
