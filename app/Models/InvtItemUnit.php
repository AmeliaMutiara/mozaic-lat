<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvtItemUnit extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table        = 'invt_item_unit';
    protected $primaryKey   = 'item_unit_id';
    protected $guarded = [
        'updated_at',
        'created_at'
    ];
}
