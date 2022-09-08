<?php


namespace App\Services;

use App\Helpers\Helper;
use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class PageService
{

    /**
     * @var $pageRepository
     */
    protected $pageRepository;


    /**
     * QuizFormService constructor.
     * @param PageRepository $pageRepository
     */
    public function __construct(PageRepository $pageRepository)
    {

        $this->pageRepository = $pageRepository;

    }


    /**
     * @return JsonResponse
     */
    public function getAll()
    {
        return response()->json([
            'status_code'      => 200,
            'messages'         => config('status.status_code.200'),
            'page_config_info' => $this->pageRepository->all()
        ]);
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {

        if($this->pageRepository->get($id))
            return response()->json(['status_code' => 200, 'messages'=>config('status.status_code.200'), 'quiz_info'=>$this->pageRepository->get($id)]);

        return response()->json(['status_code' => 302, 'messages'=>config('status.status_code.302'), 'quiz_info'=>"Data not found"]);

    }


    /**
     * @param $request
     * @return JsonResponse
     */
    public function createItem($request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json([
                'status_code' => '400',
                'messages'=>config('status.status_code.400'),
                'error' =>  $validator->errors()->first()
            ]);

        }

        $input = $request->all();
        $input['id'] = time().rand(1000,9000);

        if($request->hasFile('logo')) {
            $input['logo_url']  = Helper::base64PageLogoUpload("page/images", $request->logo);
        }

        if($request->hasFile('banner')) {
            $input['banner_url'] = Helper::base64PageLogoUpload("page/images", $request->banner);
        }

        $this->pageRepository->create($input);

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
            'title' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json([
                'status_code' => '400',
                'messages'=>config('status.status_code.400'),
                'error' =>  $validator->errors()->first()
            ]);

        }

        DB::beginTransaction();

        try {

            $input = $request->all();

            $config_data =  Page::find($request->id);

            if($request->hasFile('logo')) {
                $input['logo']  = Helper::base64PageLogoUpload("page/images", $request->logo);
            } else{
                $input['logo']  = $config_data->logo;
            }

            if($request->hasFile('banner')) {
                $input['banner']  = Helper::base64PageLogoUpload("page/images", $request->banner);
            }else{
                $input['banner']  = $config_data->banner;
            }

           // $this->pageRepository->create($input);

            $this->pageRepository->update($input, $request->id);

        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status_code' => '424',
                'messages'=>config('status.status_code.424'),
                'error' => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json(['status_code' => 200, 'messages'=>config('status.status_code.200')]);

    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteItem($id)
    {

        DB::beginTransaction();

        try {

            $this->pageRepository->delete($id);

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json(['status_code' => '424',
                'messages'=>config('status.status_code.424'),
                'error' => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json(['status_code' => 200, 'messages'=>config('status.status_code.200')]);

    }

}
