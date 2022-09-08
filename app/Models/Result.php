<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'quiz_id', 'question_id','answer'];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function quiz() {
        return $this->belongsTo(Quiz::class,'quiz_id');
    }

    public function question() {
        return $this->belongsTo(QuizFormField::class,'question_id');
    }

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
