<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcctJournalVoucherItem extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table        = 'acct_journal_voucher_item';
    protected $primaryKey   = 'journal_voucher_item_id';
    protected $with         = ['account'];
    protected $guarded = [
        'deleted_at',
        'updated_at',
        'created_at'
    ];

    public function account() {
        return $this->belongsTo(AcctAccount::class,'account_id','account_id');
    }

    public function item() {
        return $this->belongsTo(AcctJournalVoucher::class,'journal_voucher_id','journal_voucher_id');
    }
}
