<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoice extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table    = 'purchase_invoice';
    protected $primaryKey = 'purchase_invoice_id';
    protected $guarded =[
        'created_at',
        'updated_at',
    ];
    public function item(){
        return $this->hasMany(PurchaseInvoiceItem::class,'purchase_invoice_id','purchase_invoice_id');
    }
    public function supplier(){
        return $this->belongsTo(CoreSupplier::class,'supplier_id','supplier_id');
    }
}
