<?php

namespace App\Http\Controllers;


use App\Services\CrudHistoryService;
use Auth;
use Illuminate\Http\Request;

class CrudHistoryController extends Controller
{
    //
    protected $crudHistoryService;

    public function __construct(CrudHistoryService $crudHistoryService)
    {

        //$this->middleware("auth");
        $this->crudHistoryService = $crudHistoryService;

    }

    public function showAll(){
        if(Auth::user()->can('history-list')) {

            return $this->crudHistoryService->getAll();

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }
    }




    public function index($post_id)
    {

        if(Auth::user()->can('history-list')) {

            return $this->crudHistoryService->paginateData($post_id);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }

    }

    public function deleteAllHistory(Request $request){
//        return $request->all();
        if(Auth::user()->can('history-list')) {



            return $this->crudHistoryService->deletePostHistoy($request);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }
    }
}
