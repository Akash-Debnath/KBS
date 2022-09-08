<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id','user_id','comment_body','status'
    ];

    public function user()
    {

        return $this->belongsTo(User::class,'user_id');

    }

    public function article()
    {
        return $this->belongsTo(Article::class,'post_id');
    }

    public function faq()
    {
        return $this->belongsTo(Faq::class,'post_id');
    }

    public function getCreatedAtAttribute($date)
    {
        return date('j M, Y', strtotime($date));
    }


    public function getUpdatedAtAttribute($date)
    {
        return date('j M, Y', strtotime($date));
    }
}
