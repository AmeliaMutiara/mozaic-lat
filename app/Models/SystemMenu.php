<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemMenu extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;
    protected $table        = 'system_menu'; 
    protected $primaryKey   = 'id_menu';
    protected $guarded = [
        
    ];
}
