<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreferenceVoucher extends Model
{
    use HasFactory;
    use SoftDeletes, CreatedUpdatedID;
    protected $table        = 'preference_voucher';
    protected $primaryKey   = 'voucher_id';
    protected $guarded = [
        'updated_at',
        'created_at'
    ];
}
