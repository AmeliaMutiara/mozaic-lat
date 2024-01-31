<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoiceChangeDate extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table = 'purchase_invoice_change_date';
    protected $primaryKey = 'purchase_invoice_change_date_id';
    protected $guarded = [
        'created_at',
        'updated_at'
    ];
}
