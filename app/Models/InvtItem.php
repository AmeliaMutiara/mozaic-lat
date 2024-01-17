<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvtItem extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;

    protected $table = 'invt_item';
    protected $primaryKey = 'item_id';
    protected $guarded = [
        'updated_at',
        'created_at'
    ];
    public function category(){
        return $this->belongsTo(InvtItemCategory::class,'item_category_id','item_category_id');
    }
    public function package(){
        return $this->hasOne(InvtItemPackage::class,'item_id','item_id');
    }
    public function barcode(){
        return $this->hasMany(InvtItemBarcode::class,'item_id','item_id');
    }
    public function unit(){
        return $this->belongsTo(InvtItemUnit::class,'item_unit_id','item_unit_id');
    }
}
