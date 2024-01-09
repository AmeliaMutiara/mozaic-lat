<?php

namespace App\Models;

use App\Traits\CreatedUpdatedID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemMenuMapping extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedID;

    protected $table        = 'system_menu_mapping';
    protected $primarykey   = 'menu_mapping_id';
    protected $guarded      = [];
    
}
