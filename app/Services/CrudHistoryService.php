<?php


namespace App\Services;


use App\Repositories\CrudHistoryRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CrudHistoryService
{

    protected $crudHistoryRepository;

    public function __construct(CrudHistoryRepository $crudHistoryRepository)
    {

        $this->crudHistoryRepository = $crudHistoryRepository;

    }

    public function getAll()
    {

        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'history_list' => $this->crudHistoryRepository->getAllWithPagination()

        ]);

    }

    public function paginateData($post_id)
    {

        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'history_list' => $this->crudHistoryRepository->getWithPagination($post_id)

        ]);

    }

    public function deletePostHistoy($request){
        DB::beginTransaction();

        try {

            $this->crudHistoryRepository->delete($request);

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

}