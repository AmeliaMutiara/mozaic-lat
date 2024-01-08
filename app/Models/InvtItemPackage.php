<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvtItemPackage extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table        = 'invt_item_package';
    protected $primaryKey   = 'invt_package_id';
    protected $guarded = [
        'updated_at',
        'created_at'
    ];

    public function category() {
        return $this->belongsTo(InvtItemCategory::class, 'item_category_id');
    }

    // public function item() {
    //     return $this->belongsTo(InvtItem::class, 'item_id');
    // }

    // public function unit() {
    //     return $this->belongsTo(InvtItemUnit::class, 'item_unit_id');
    // }
}
