<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalVoucher extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table = 'acct_journal_voucher';
    protected $primaryKey = 'journal_voucher_id';
    public function items(){
        return $this->hasMany(JournalVoucherItem::class,"journal_voucher_id","journal_voucher_id");
    }
    protected $guarded = [
        'updated_at',
        'created_at'
    ];
}