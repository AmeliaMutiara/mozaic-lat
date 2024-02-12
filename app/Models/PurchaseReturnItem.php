<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturnItem extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table = 'purchase_return_item';
    protected $primaryKey = 'purchase_item_id';
    protected $guarded = [
        'created_at',
        'updated_at'
    ];
    public function item() {
        return $this->belongsTo(InvtItem::class,'item_id','item_id');
    }
    public function name() {
        return $this->belongsTo(InvtItemUnit::class,'item_unit_id','item_unit_id');
    }
}
