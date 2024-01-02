<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvtItemCategory extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table        = 'invt_item_category';
    protected $primaryKey   = 'item_category_id';
    protected $guarded = [
        'item_category_code',
        'item_category_name',
        'item_category_remark',
        'margin_precentage',
        'company_id',
        'updated_at',
        'created_at'
    ];
}