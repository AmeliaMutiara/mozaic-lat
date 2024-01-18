<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcctAccountSetting extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table        ='acct_account_setting';
    protected $primaryKey   ='account_setting_id';
    protected $guarded      =[

    ];
}
