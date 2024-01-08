<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvtWarehouse extends Model
{
    use HasFactory;
    use SoftDeletes, CreatedUpdatedID;
    protected $table        = 'invt_warehouse';
    protected $primaryKey   = 'warehouse_id';
    protected $guarded = [
        'updated_at',
        'created_at'
    ];
}
