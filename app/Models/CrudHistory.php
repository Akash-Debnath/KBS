<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrudHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','post_id','operation_type'];

    public function Linkable()
    {

        return $this->morphTo();

    }

    public function user()
    {

        return $this->belongsTo(User::class,'user_id');

    }


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
}
