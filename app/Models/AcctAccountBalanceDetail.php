<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcctAccountBalanceDetail extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    public $timestamps = false;
    protected $table        = 'acct_account_balance_detail';
    protected $primaryKey   = 'acct_balance_detail_id';
    protected $guarded = [
        'deleted_at',
        'updated_at',
        'created_at'
    ];

    public function account() {
        return $this->belongsTo(AcctAccount::class,'account_id','account_id');
    }
}
