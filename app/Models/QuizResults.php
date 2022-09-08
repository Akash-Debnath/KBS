<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResults extends Model
{
    use HasFactory;
    protected $fillable = ['quiz_id', 'user_id', 'attempt','score', 'status'];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function quiz() {
        return $this->belongsTo(Quiz::class,'quiz_id');
    }

    public function getCreatedAtAttribute($date)
    {
        // return date('j M, Y', strtotime($date));
        return date('d/m/y', strtotime($date));
    }
}
