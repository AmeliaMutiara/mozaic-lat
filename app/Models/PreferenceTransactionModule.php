<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreferenceTransactionModule extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table        = 'preference_transaction_module';
    protected $primaryKey   = 'transaction_module_id';
    protected $guarded = [
        'deleted_at',
        'updated_at',
        'created_at'
    ];
}
