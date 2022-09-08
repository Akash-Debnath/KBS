<?php


namespace App\Services;


use App\Repositories\NotificatrionRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NotificationService
{

    protected $notificatrionRepository;

    public function __construct(NotificatrionRepository $notificatrionRepository)
    {
        $this->notificatrionRepository = $notificatrionRepository;
    }

    public function paginateData(){

        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'notification_list' => $this->notificatrionRepository->getWithPagination()

        ]);
    }

    
    public function deleteItem($id){

        DB::beginTransaction();

        try {

            $this->notificatrionRepository->delete($id);

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