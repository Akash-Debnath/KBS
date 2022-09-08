<?php


namespace App\Services;


use App\Repositories\ResultRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Helper;
class ResultService
{

    protected $resultRepository;

    public function __construct(ResultRepository $resultRepository)
    {
        $this->resultRepository = $resultRepository;
    }

    public function paginateData(){
        
        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'result_list' => $this->resultRepository->getWithPagination()

        ]);
    }

    public function quizResultsPaginateData(){
        
        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'result_list' => $this->resultRepository->getQuizResults()

        ]);
    }

    public function getUserQuizResults($request){
        
        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'user_result_list' => $this->resultRepository->userQuizResults($request)
        ]);
    }

    public function quizResultsDetails($request){
        
        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'quiz_result_details' => $this->resultRepository->getQuizResultsDetails($request)

        ]);
    }

    public function getUserQuizList($request){
        
        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'user_quiz_list' => $this->resultRepository->userQuizList($request)

        ]);
    }

    public function getUserQuizResultList($request){
        
        $quizResultList = $this->resultRepository->userQuizResultList($request);
        foreach($quizResultList as $key=>$quizResult) {
            $resultData = $this->resultRepository->getResultData($quizResult);
            $quizResultList[$key]->result = Helper::calculateResult($quizResult->quiz->total_marks, $quizResult->quiz->number_of_questions, $resultData);     
        }
        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'user_quiz_result_list' => $quizResultList

        ]);
    }

    public function getUserQuizResultDetails($request){
        
            $quizResultDetails = $this->resultRepository->getResultData($request);
            $quizResultDetails[0]->result = Helper::calculateResult( $quizResultDetails[0]->quiz->total_marks, $quizResultDetails[0]->quiz->number_of_questions, $quizResultDetails);     
       
        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'result_details' => $quizResultDetails

        ]);
    }

    public function createItem($request){
        $validator = Validator::make($request->all(),[

            'quiz_id' => "required",

        ]);

        if($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'errors'      => $validator->errors()->all()
            ]);
        }

        $input = [];
        $quesAndAns = json_decode($request->ques_and_ans,true);

        DB::beginTransaction();

        try {

            if(!$request->user_id) {
                $MAC = exec('getmac');
                $mac_address = strtok($MAC, ' ');
                $request->user_id = $mac_address ? $mac_address : Helper::getClientIpAddress();
            }
            $attempDetails =  $this->resultRepository->checkAttempt($request->user_id, $request->quiz_id);


            if($attempDetails) {
              $attempCount =  $attempDetails->attempt+1;
            } else {
                $attempCount = 1;
            }
            foreach($quesAndAns as $key=>$value) {
                $input['id'] = time().rand(1000,9000);
                $input['user_id'] = $request->user_id;
                $input['quiz_id'] = $request->quiz_id;
                $input['question_id'] = $key;
                $input['answer'] = $value;
                $input['attempt'] = $attempCount;
                $this->resultRepository->create($input);
            }
            // $this->resultRepository->create($input);
            $input['score'] = $request->score;
            $input['status'] = $request->status;
            $this->resultRepository->createQuizResult($input);

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status_code' => 424,
                'messages'    => config('status.status_code.424'),
                'error'       => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status_code' => 200,
            'messages'=>config('status.status_code.200'),
        ]);
    }

    
    public function deleteItem($id){

        DB::beginTransaction();

        try {

            $this->resultRepository->delete($id);

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status_code' => 424,
                'messages'    => config('status.status_code.424'),
                'error'       => $e->getMessage()
            ]);

        }

        DB::commit();

        return response()->json([

            'status_code' => 200,
            'messages'    => "Comment Deleted Successfully"

        ]);
    }
}