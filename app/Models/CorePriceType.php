<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorePriceType extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table = 'core_price_type';
    protected $primaryKey = 'price_type_id';
    public function salesPrice(){
        return $this->hasMany(SalesRoomPrice::class,'price_type_id','price_type_id');
    }
    protected $guarded = [
        'created_at',
        'updated_at'
    ];
}
