<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Model;
//use Jenssegers\Mongodb\Eloquent\Model;

class Faq extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'user_id',
        'category_id',
        'article_id',
        'en_title',
        'bn_title',
        'tag',
        'slug',
        'en_body',
        'status',
        'publish_date',
        'is_notifiable'

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @param $date
     * @return false|string
     */
    public function getCreatedAtAttribute($date)
    {
        return date('j M, Y', strtotime($date));
    }
    
    public function contents()
    {

        return $this->hasMany(Content::class, 'article_id');

    }
    /**
     * @param $date
     * @return false|string
     */
    public function getUpdatedAtAttribute($date)
    {
        return date('j M, Y', strtotime($date));
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'id');
    }

    public function history()
    {
        return $this->morphMany(CrudHistory::class, 'linkable')->with('user');
    }
}
