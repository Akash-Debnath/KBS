<?php


namespace App\Services;


use App\Helpers\Helper;
use App\Repositories\QuizRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class QuizService
{
    /**
     * @var QuizRepository
     */
    protected $quizRepository;


    /**
     * QuizFormService constructor.
     * @param QuizRepository $quizRepository
     */
    public function __construct(QuizRepository $quizRepository)
    {

        $this->quizRepository = $quizRepository;

    }


    /**
     * @return JsonResponse
     */
    public function getAll()
    {
        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'quiz_form_list' => $this->quizRepository->all()
        ]);
    }

    public function getUnauthorisedQuiz(){
        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'quiz_form_list' => $this->quizRepository->allWithZero()
        ]);
    }

    public function getAuthorisedQuiz($role){
        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'quiz_form_list' => $this->quizRepository->selfWithZero($role)
        ]);
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {

        if($this->quizRepository->get($id))
            return response()->json([
                'status_code' => 200,
                'messages'    => config('status.status_code.200'),
                'quiz_info'   => $this->quizRepository->get($id)
            ]);

        return response()->json([
            'status_code' => 302,
            'messages'  => config('status.status_code.302'),
            'quiz_info' => "Data not found"
        ]);

    }


    /**
     * @param $request
     * @return JsonResponse
     */
    public function createItem($request)
    {
        $validator = Validator::make($request->all(),[
            'article_id'            => 'required',
            'quiz_form_id'          => 'required',
            'name'                  => 'required|unique:quizzes,name',
            'duration'              => 'required',
            'total_marks'           => 'required',
            'number_of_questions'   => 'required',
            'status'                => 'required',
        ]);

        if($validator->fails()) {

            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'errors'       => $validator->errors()->all()
            ]);
        }

        $input = $request->all();
        $input['slug'] = Helper::slugify($input['name']);
        $input['id']   = time().rand(1000,9000);


        DB::beginTransaction();
        try {

            $selectedArticleID = array();
            foreach ($input['article_id'] as $articleInfo){
                array_push($selectedArticleID,$articleInfo['id'].'/'.$articleInfo['slug']);
            }
            $input['article_info'] = join(",",$selectedArticleID);
            $result_array = explode(",",$input['role_id']);

            if (in_array('1',$result_array)){
                $this->quizRepository->create($input);
            }else{
                array_push($result_array,'1');
                $input['role_id'] = implode(',',$result_array);
                $this->quizRepository->create($input);
            }
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

        return response()->json(['status_code' => 200, 'messages'=>config('status.status_code.200')]);
    }


    /**
     * @param $request
     * @param $id
     * @return JsonResponse
     */
    public function updateItem($request)
    {

        $validator = Validator::make($request->all(),[
            'quiz_form_id'          => 'required',
            'name'                  => 'required',
            'duration'              => 'required',
            'total_marks'           => 'required',
            'number_of_questions'   => 'required',
            'status'                => 'required',

        ]);

        if($validator->fails()) {
            return response()->json([
                'status_code'       => 400,
                'messages'          => config('status.status_code.400'),
                'errors'            => $validator->errors()->all()
            ]);
        }

        DB::beginTransaction();

        try {

            $input = $request->all();
            $input['slug'] = Helper::slugify($input['name']);

            if (!empty($input['article_id'])){
                $selectedArticleID = array();
                foreach ($input['article_id'] as $articleInfo){
                    $articleInfo['slug'] = Helper::slugify($articleInfo['en_title']);
                    array_push($selectedArticleID,$articleInfo['id'].'/'.$articleInfo['slug']);
                }
//                $input['article_info'] = join(",",$selectedArticleID);
                $input['article_info'] = join(",",$selectedArticleID);
            }
            $result_array = explode(",",$input['role_id']);

            if (in_array('1',$result_array)){
                $this->quizRepository->update($input, $request->id);
            }else{
                array_push($result_array,'1');
                $input['role_id'] = implode(',',$result_array);
                $this->quizRepository->update($input, $request->id);
            }

        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status_code' => 424,
                'messages'=>config('status.status_code.424'),
                'error' => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status_code' => 200,
            'messages'=>config('status.status_code.200')
        ]);

    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteItem($id)
    {

        DB::beginTransaction();

        try {

            $this->quizRepository->delete($id);

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status_code' => 424,
                'messages' => config('status.status_code.424'),
                'error' => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status_code' => 200,
            'messages' => config('status.status_code.200')
        ]);

    }


    /**
     * @return JsonResponse
     */
    public function paginateData($request)
    {
        return response()->json([
            'status_code' => 200,
            'messages' => config('status.status_code.200'),
            'quiz_list' => $this->quizRepository->getWithPagination($request)
        ]);

    }
}
