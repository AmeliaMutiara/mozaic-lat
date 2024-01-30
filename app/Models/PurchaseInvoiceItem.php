<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoiceItem extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table = 'purchase_invoice_item';
    protected $primaryKey = 'purchase_invoice_item_id';
    public function item(){
        return $this->belongsTo(InvtItem::class,'item_id','item_id');
    }
    public function unit(){
        return $this->belongsTo(InvtItemUnit::class,'item_unit_id','item_unit_id');
    }
    public function data(){
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id','purchase_invoice_id');
    }
    protected $guarded = [
        'created_at',
        'updated_at'
    ];
}
