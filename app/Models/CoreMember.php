<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreMember extends Model
{
    protected $table = 'core_member';
    protected $primarykey = 'member_id';
    protected $guarded = [
        'last_update',
    ];
}
