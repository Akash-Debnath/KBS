<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizFormField extends Model
{
    protected $collection = 'quiz_form_fields';
    protected $primaryKey = 'id';
    /**
     * @var string
     */
    protected $table = 'quiz_form_fields';

    /**
     * @var string[]
     */
    protected $fillable = ["id", "quiz_form_id", "f_label", "f_name", "f_id", "f_class", "f_type", "f_option_value", "f_default_value",
        "f_max_value", "f_sort_order", "f_required"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function quizForm()
    {

        return $this->belongsToMany(QuizForm::class);

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
