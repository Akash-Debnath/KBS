<?php


namespace App\Repositories;
use DB;

use App\Models\Result;
use App\Models\QuizResults;

class ResultRepository
{
    
    public function getWithPagination() {

        // return Result::with('user')->orderBY('created_at','DESC')->paginate(20);
        return Result::with('user')->select('user_id')->groupby('user_id')->orderby('created_at','DESC')->paginate(20);

    }

    public function getQuizResults() {

       return  QuizResults::with(['user','quiz'])->select('quiz_id','user_id',DB::raw("count(id) as attempt"),DB::raw("sum(if(status='1',1,0)) as pass"),DB::raw("sum(if(status='0',1,0)) as fail"))->groupby('quiz_id')->paginate(20);
    }

    public function getQuizResultsDetails($request) {

        return  QuizResults::with(['user','quiz'])->where(['quiz_id'=>$request->quiz_id,'status'=>$request->status])->orderby('attempt','DESC')->paginate(20);
     }

    public function userQuizResults($request) {

        // return  QuizResults::with(['user','quiz'])->select('quiz_id','user_id',DB::raw("count(id) as attempt"),DB::raw("sum(if(status='1',1,0)) as pass"),DB::raw("sum(if(status='0',1,0)) as fail"))->groupby('quiz_id')->paginate(20);

        $query =  QuizResults::with(['user','quiz'])->where('user_id',$request->user_id);
        if($request->start_date && $request->start_date) {
            $query->whereBetween('created_at',[ date('y-m-d', strtotime($request->start_date)), date('y-m-d', strtotime($request->end_date))]);
        }
        return $query->get();
     }

    public function UserQuizList($request) {
        return Result::select('id','user_id','quiz_id',DB::raw("MAX(attempt) as attempt"))->with('user','quiz')->where('user_id',$request->userId)->groupby('quiz_id')->orderby('created_at','DESC')->paginate(20);
    }

    public function UserQuizResultList($request) {
        return Result::with('user','quiz')->where(['user_id'=>$request->userId,'quiz_id'=>$request->quizId])
        ->groupby('attempt')->orderby('id','DESC')->paginate(20);
    }


    public function getResultData($quizResult) {
        return Result::with('user','quiz','question')->where(['user_id'=>$quizResult->user_id,'quiz_id'=>$quizResult->quiz_id,'attempt'=>$quizResult->attempt])
        ->get();
    }
    

    public function create(array $data){

        $dataObj =  new Result();
        $dataObj->id = $data['id'];
        $dataObj->user_id = $data['user_id'];
        $dataObj->quiz_id = $data['quiz_id'];
        $dataObj->question_id = $data['question_id'];
        $dataObj->answer = $data['answer'];
        $dataObj->attempt = $data['attempt'];
        return $dataObj->save();
    }

    public function createQuizResult(array $data){

        $dataObj =  new QuizResults();
        $dataObj->id = time().rand(1000,9000);;
        $dataObj->quiz_id = $data['quiz_id'];
        $dataObj->user_id = $data['user_id'];
        $dataObj->attempt = $data['attempt'];
        $dataObj->score = $data['score'];
        $dataObj->status = $data['status'];
        return $dataObj->save();
    }

    public function checkAttempt($userId, $quizId) {
        $attemptDetails = Result::where(['user_id'=>$userId, 'quiz_id'=>$quizId])->orderby('created_at','DESC')->first();
        return $attemptDetails;
    }
    public function delete($id) {
        return Result::find($id)->delete();
    }

}