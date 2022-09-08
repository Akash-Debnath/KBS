<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['title','role_id', 'slug', 'status'];

    public function user()
    {

        return $this->belongsTo(User::class);

    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function getCreatedAtAttribute($date)
    {

        return date('j M, Y', strtotime($date));

    }

    public function history()
    {
        return $this->morphMany(CrudHistory::class, 'linkable')->with('user');
    }

    /**
     * @param $date
     * @return string
     */
    public function getUpdatedAtAttribute($date)
    {

        return date('j M, Y', strtotime($date));

    }
}
