<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesMerchant extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table = 'sales_merchant';
    protected $primaryKey = 'mercahnt_id';
    public function category(){
        return $this->hasMany(CoreBuilding::class,'building_id','building_id');
    }
    protected $guarded = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
