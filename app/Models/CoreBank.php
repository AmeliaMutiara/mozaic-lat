<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreBank extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table        = 'core_bank';
    protected $primaryKey   = 'bank_id';
    protected $guarded = [
        'deleted_at',
        'updated_at',
        'created_at'
    ];

    public function account() {
        return $this->belongsTo(AcctAccount::class,'account_id','account_id');
    }
}
