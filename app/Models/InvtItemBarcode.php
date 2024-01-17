<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvtItemBarcode extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table        ='invt_item_barcode';
    protected $primaryKey   ='item_barcode_id';
    protected $guarded      =[
        'updated_at',
        'created_at'
    ];
    public function unit(){
        return $this->belongsTo(InvtItemUnit::class,'item_unit_id','item_unit_id');
    }
}
