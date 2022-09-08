<?php


namespace App\Services;

use Exception;
use App\Helpers\Helper;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Repositories\FaqRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FaqService
{
    /**
     * @var FaqRepository
     */
    protected $faqRepository;

    public function __construct(FaqRepository $faqRepository)
    {

        $this->faqRepository = $faqRepository;

    }

    /**
     * @return JsonResponse
     */
    public function getAll()
    {

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'faq_list'    => $this->faqRepository->all()
        ]);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createItem(Request $request)
    {
        $validator = Validator::make($request->all(),[

            'en_title'      => 'required|string|min:3|max:200',
            'category_id'   => 'required',
       ]);

        if($validator->fails()) {

            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'errors'      => $validator->errors()->all()
            ]);

        }

        $input = $request->all();
        // $input['id'] = time().rand(1000,9000);
        $input['user_id'] = auth()->user()->id;
        $input['publish_date'] = date('Y-m-d H:i:s');

        DB::beginTransaction();

        try {

            $this->faqRepository->create($input);
            $faq = $this->getItemById($input['id']);
             //notification
             if($faq->is_notifiable == 1) {
                $faqNotification = $this->sendFaqNotification(  $faq->id , 'add');
             }
           


            $faq->history()->create([
                'user_id'           => $input['user_id'],
                'post_id'           => $input['id'],
                'operation_type'    => $request->route()->getActionMethod()
            ]);

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

        return response()->json(['status_code' => 201, 'messages'=>config('status.status_code.201')]);

    }


    public function getItemById($id)
    {
        return $this->faqRepository->get($id);
    }

    public function sendFaqNotification($faq_id, $type) {

        $faq = $this->getItemById($faq_id);
        $users = $this->faqRepository->getAllUsers();
        //return $notifications = Helper::sendFaqNotification($faq, $users, $type);
        SendEmailJob::dispatch($faq, $users , $type, 'faq')
        ->delay(now()->addSeconds(3));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {
        if($this->faqRepository->get($id))
            return response()->json([
                'status_code' => 200,
                'messages'=>config('status.status_code.200'),
                'faq_info'=>$this->faqRepository->get($id)
            ]);

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'faq_info'    => "Data not found"
        ]);

    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function updateItem($request)
    {

        $validator = Validator::make($request->all(),[
            'en_title'      => 'required|string|min:3|max:100',
            'category_id'   => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'errors'      => $validator->errors()->all()
            ]);
        }

        $input = $request->all();
        $input['slug'] = Helper::slugify($input['en_title']);

        DB::beginTransaction();

        try {

            $this->faqRepository->update($input, $input['id']);

            $faq = $this->faqRepository->get($input['id']);


            $faq->history()->create([
                'user_id' => auth()->user()->id,
                'post_id' => $request->id,
                'operation_type' => $request->route()->getActionMethod()
            ]);

            //notification
            if($faq->is_notifiable == 1) {
                $faqNotification = $this->sendFaqNotification( $faq->id , 'update');
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

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200')
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

            //notification
            $faq = $this->getItemById($id);

            // if($faq->is_notifiable == 1) {
            //     $faqNotification = $this->sendFaqNotification( $id, 'delete');
            // }

            $faq->history()->create([
                'user_id' => auth()->user()->id,
                'post_id' => $id,
                'operation_type' => 'delete'
            ]);

            $this->faqRepository->delete($id);

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
     * @return JsonResponse
     */
    public function paginateData(Request $request)
    {

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'faq_list'    => $this->faqRepository->getWithPagination($request)
        ]);

    }

    public function getFaqList(Request $request)
    {

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'faq_list'    => $this->faqRepository->getFaqListForFrontend($request)
        ]);

    }

    public function faqStatusChange($request)
    {
        $faq = $this->getItemById($request->id);

        $faq->history()->create([
            'user_id' => auth()->user()->id,
            'post_id' => $request->id,
            'operation_type' => 'status changed'
        ]);

        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'faq_info'     => $this->faqRepository->changeStatus($request)

        ]);

    }

    public function searchFaq($searchString)
    {
        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'faq_list' => $this->faqRepository->search($searchString)
//            'faq_list' => $searchString

        ]);

    }

}
