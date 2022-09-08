<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class groupRoles extends Model
{
    use HasFactory;

    protected $fillable = ['group_id','role_id'];

    public $timestamps = false;
}
