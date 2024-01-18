<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreSection extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table        = 'core_section'; 
    protected $primaryKey   = 'section_id';
    
    protected $guarded = [
        'section_id',
        'created_at',
        'updated_at',
    ];

}
