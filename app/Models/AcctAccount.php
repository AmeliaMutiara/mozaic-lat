<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcctAccount extends Model
{
    use HasFactory;
    protected $table        = 'acct_account';
    protected $primaryKey   = 'account_id';
    protected $guarded = [
        'deleted_at',
        'updated_at',
        'created_at'
    ];

    // public function bank()
    // {
    //     return $this->hasMany(CoreBank::)
    // }
}
