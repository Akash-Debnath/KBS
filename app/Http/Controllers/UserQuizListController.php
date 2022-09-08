<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResultService;
use Auth;

class UserQuizListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $resultService;

    public function __construct(ResultService $resultService)
    {
        $this->resultService = $resultService;
    }

    public function index(Request $request)
    {
        //
//        return $this->resultService->getUserQuizList($request);
        if(Auth::user()->can('result-list')) {

            return $this->resultService->getUserQuizList($request);
            // return $userId;

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }
    }

    public function getQuizResultList(Request $request)
    {
        //
        if(Auth::user()->can('result-list')) {

            return $this->resultService->getUserQuizResultList($request);
            // return $userId;

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }
    }

    public function getQuizResultDetails(Request $request)
    {   
        return $this->resultService->getUserQuizResultDetails($request);
        //
        // if(Auth::user()->can('result-list')) {

        //     return $this->resultService->getUserQuizResultDetails($request);
        //     // return $userId;

        // } else {

        //     return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        // }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function storeResult(Request $request)
    {   
        return $this->resultService->createItem($request);

    }
    
    public function store(Request $request)
    {

        // return $this->resultService->createItem($request);
        
    }

    


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
