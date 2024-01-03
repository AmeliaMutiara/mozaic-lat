<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvtItemCategory extends Model
{
    // use HasFactory;
    use SoftDeletes, CreatedUpdatedID;

    protected $table        = 'invt_item_category';
    protected $primaryKey   = 'item_category_id';
    protected $guarded = [
        'deleted_at',
        'updated_at',
        'created_at'
    ];
}