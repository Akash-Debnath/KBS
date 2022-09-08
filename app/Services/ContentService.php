<?php


namespace App\Services;


use App\Models\Content;
use App\Repositories\ContentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContentService
{
    protected $contentRepository;

    public function __construct(ContentRepository $contentRepository)
    {

        $this->contentRepository = $contentRepository;

    }

    public function checkArticleExist($id){

        DB::beginTransaction();

        try {

            $content_list = $this->contentRepository->articleAvailabilty($id);

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
            'messages'    => "Content Deleted Successfully",
            'content_list' => $content_list

        ]);
    }
    public function checkFaqExist($id){

        DB::beginTransaction();

        try {

            $content_list = $this->contentRepository->faqAvailabilty($id);

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
            'messages'    => "Content Deleted Successfully",
            'content_list' => $content_list

        ]);
    }

    public function articleContentList($id){

        DB::beginTransaction();

        try {

            $content_list = $this->contentRepository->articleContentShow($id);

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
            'content_list' => $content_list

        ]);
    }

    public function deleteItem($id){

        DB::beginTransaction();

        try {

            $this->contentRepository->delete($id);

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
            'messages'    => "Content Deleted Successfully"

        ]);
    }

    public function updateItem($request){
        $validator = Validator::make($request->all(),[
            'role_id' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'errors'      => $validator->errors()->all()
            ]);

        }

        DB::beginTransaction();


        try {

            $result_array = explode(",",$request->role_id);

            if (in_array('1',$result_array)){
                $this->contentRepository->update($request);
            }else{
                array_push($result_array,1);
                $request->role_id = implode(',',$result_array);
                $this->contentRepository->update($request);
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
            'messages'=>config('status.status_code.200'),
        ]);


    }

    public function getById($id){

        if ($this->contentRepository->get($id)){
            return response()->json([
                'status_code'  => 200,
                'messages'     => config('status.status_code.200'),
                'content_info' => $this->contentRepository->get($id)
            ]);
        }else{
            return response()->json([
                'status_code'  => 200,
                'messages'     => config('status.status_code.200'),
                'content_info' => "Data not found"
            ]);
        }
    }

    public function createItem($request){
//        $validator = Validator::make($request->all(),[
//            'role_id' => 'required',
//        ]);
//
//        if($validator->fails()) {
//
//            return response()->json([
//                'status_code' => 400,
//                'messages'    => config('status.status_code.400'),
//                'errors'      => $validator->errors()->all()
//            ]);
//
//        }

//        return $request->all();

        DB::beginTransaction();

        try {

            $result_array = explode(",",$request->role_id);

            if (in_array('1',$result_array)){
                $this->contentRepository->create($request);
            }else{
                array_push($result_array,1);
                $request->role_id = implode(',',$result_array);
                $this->contentRepository->create($request);
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
            'messages'=>config('status.status_code.200'),
        ]);
    }
}