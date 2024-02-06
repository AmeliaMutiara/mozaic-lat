<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalVoucherItem extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table = 'acct_journal_voucher_item';
    protected $primaryKey = 'journal_voucher_item_id';
    protected $with = ['account'];
    public function account(){
        return $this->belongsTo(AcctAccount::class,'account_id','account_id');
    }
    protected $guarded = [
        'updated_at',
        'created_at'
    ];
}