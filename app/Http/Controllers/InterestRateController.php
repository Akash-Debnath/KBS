<?php

namespace App\Http\Controllers;

use App\Services\InterestRateService;
use App\Models\InterestRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterestRateController extends Controller
{
        
    /**
     * @var InterestRateService
     */
    protected $interestRateService;

    /**
     * CategoryController constructor.
     * @param InterestRateService $interestRateService
     *
     */
    public function __construct(InterestRateService $interestRateService)
    {
        $this->interestRateService = $interestRateService;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->can('menu-list')) {
    
            return $this->interestRateService->paginateData($request);
        } else {
            return response()->json([
                'status_code' => 424,
                'messages'=>'User does not have the right permissions'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->can('menu-create')) {

            return $this->interestRateService->createItem($request);

        } else {

            return response()->json([
                'status_code' => 424,
                'messages'=>'User does not have the right permissions'
            ]);

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InterestRate  $interestRate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::user()->can('menu-list')) {

            return $this->interestRateService->getById($id);

        } else {

            return response()->json([
                'status_code' => 424,
                'messages'=>'User does not have the right permissions'
            ]);

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InterestRate  $interestRate
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InterestRate  $interestRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request);
        if(Auth::user()->can('menu-edit')) {

            return $this->interestRateService->updateItem($request);

        } else {

            return response()->json([
                'status_code' => 424,
                'messages'=>'User does not have the right permissions'
            ]);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InterestRate  $interestRate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(Auth::user()->can('menu-delete')) {

            return  $this->interestRateService->deleteItem($request->id);

        } else {

            return response()->json([
                'status_code' => 424,
                'messages'=>'User does not have the right permissions'
            ]);
        }
    }

    public function getAllInterestRate() {
        return  $this->interestRateService->getAll();
    }
}
