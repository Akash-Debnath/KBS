<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = ['article_id', 'en_body', 'role_id','bn_body'];


    public function article()
    {

        return $this->belongsTo(Article::class);

    }
}
