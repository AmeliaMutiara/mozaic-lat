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
        return $this->belongsTo(InvtItemCategory::class,'item_category_id','item_category_id')->withDefault();
    }
    public function merchant() {
        return $this->belongsTo(SalesMerchant::class,'merchant_id');
    }
    public function packets() {
        return $this->hasMany(InvtItemPackage::class,'item_id','item_id');
    }
    public function packge() {
        return $this->hasMany(InvtItemPackge::class,'item_id','item_id');
    }
    public function stock() {
        return $this->hasOne(InvtItemStock::class,'item_id','item_id');
    }
}
