<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcctAccount extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table        = 'acct_account';
    protected $primaryKey   = 'account_id';
    protected $guarded = [
        'deleted_at',
        'updated_at',
        'created_at'
    ];

}
