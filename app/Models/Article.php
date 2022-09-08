<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
class Article extends Model
{
    protected $collection = 'articles';
    protected $primaryKey = 'id';
    /**
     * @var string[]
     */
    protected $fillable = [
        'id', 'user_id', 'category_id', 'en_title', 'bn_title', 'tag', 'slug', 'en_short_summary', 'bn_short_summary', 'thumbnail_img', 'banner_img','commentable_status', 'status', 'publish_date','is_notifiable'
    ];

    /*public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {

        return $this->belongsTo(User::class);

    }

    public function contents()
    {

        return $this->hasMany(Content::class);

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {

        return $this->belongsTo(Category::class);

    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'id');
    }

//    public function quiz()
//    {
//        return $this->belongsTo(Article::class,'article_id');
//    }

    /**
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {

        return date('j M, Y', strtotime($date));

    }

    /**
     * @param $date
     * @return string
     */
    public function getUpdatedAtAttribute($date)
    {

        return date('j M, Y', strtotime($date));

    }

    public function media(){
        return $this->morphMany(Media::class, 'mediable');
    }

    public function history()
    {
        return $this->morphMany(CrudHistory::class, 'linkable')->with('user');
    }
}
