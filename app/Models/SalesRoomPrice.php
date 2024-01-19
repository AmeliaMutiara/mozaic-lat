<?php

namespace App\Models;


use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesRoomPrice extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table = 'sales_room_price';
    protected $primaryKey = 'room_price_id';
    protected $with = ['type'];
    public function room(){
        return $this->belongsTo(CoreRoom::class,'room_id','room_id');
    }
    protected $guarded =[
        'created_at',
        'updated_at'
    ];
    // protected static function booted()
    // {
    //     static::addGlobalScope(new NotDeletedScope);
    // }
}
