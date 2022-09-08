<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicPageFile extends Model
{
    use HasFactory;

    protected $table = "dynamic_page_files";
    protected $fillable = ['dynamic_page_id', 'file_name'];
}
