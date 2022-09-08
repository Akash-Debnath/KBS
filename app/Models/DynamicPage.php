<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicPage extends Model
{
    use HasFactory;

    protected $table = 'dynamic_pages';
    protected $fillable = ['id', 'title', 'menu_id', 'short_summary', 'en_body', 'banner_img', 'status'];

    public function allMenu()
    {
        return $this->hasOne(Menu::class, 'id', 'menu_id');
    }

    public function file()
    {
        return $this->hasMany(DynamicPageFile::class, 'dynamic_page_id', 'id');
    }
}
