<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseReturn extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table = 'purchase_return';
    protected $primaryKey = 'purchase_return_id';
    protected $guarded = [
        'created_at',
        'updated_at'
    ];
    public function items() {
        return $this->hasMany(PurchaseReturnItem::class,'purchase_return_id','purchase_return_id');
    }
    public function supplier() {
        return $this->belongsTo(CoreSupplier::class,'supplier_id','supplier_id');
    }
}
