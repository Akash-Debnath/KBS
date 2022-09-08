<?php

namespace App\Http\Controllers;

use App\Models\QuizFormField;

class QuizTakeController extends Controller
{
    public function index($id){
        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'quiz_form_field_list' => $this->getFormListUsingForm($id)
        ]);
    }


    public function getFormListUsingForm($id)
    {

//        dd(QuizFormField::where('quiz_form_id', $id)->inRandomOrder()->limit(3)->paginate(1));

        return QuizFormField::where('quiz_form_id', $id)->inRandomOrder()->limit(1)->paginate(1);

    }

    public function totalCount($id){

        $total_quiz_count = count(QuizFormField::where('quiz_form_id', $id)->get());

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'total_quiz_count' => $total_quiz_count
        ]);
    }



}
