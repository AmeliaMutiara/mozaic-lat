<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreferenceCompany extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table        ='preference_company';
    protected $primaryKey   ='company_id';
    protected $guarded = [
        'updated_at',
        'created_at'
    ];
}
