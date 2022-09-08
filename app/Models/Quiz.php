<?php

namespace App\Models;

 use Illuminate\Database\Eloquent\Model;
//use Jenssegers\Mongodb\Eloquent\Model;

class Quiz extends Model
{
    /**
     * @var string
     */
    protected $table = 'quizzes';

    /**
     * @var string[]
     */
    protected $fillable = [
        "quiz_form_id",
        "name",
        "slug",
        "duration",
        "total_marks",
        "number_of_questions",
        "status",
        "is_authorized",
        "role_id",
        'start_date',
        'end_date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quizForm(){

        return $this->belongsTo(QuizForm::class);

    }

//    public function articles()
//    {
//        return $this->hasMany(Quiz::class,'id');
//    }

    /**
     * @param $date
     * @return false|string
     */
    public function getCreatedAtAttribute($date)
    {
        return date('j M, Y', strtotime($date));
    }

    /**
     * @param $date
     * @return false|string
     */
    public function getUpdatedAtAttribute($date)
    {
        return date('j M, Y', strtotime($date));
    }

    // public function getStartDateAttribute($date)
    // {
    //     return date('j M, Y', strtotime($date));
    // }

    // public function getEndDateAttribute($date)
    // {
    //     return date('j M, Y', strtotime($date));
    // }

}
