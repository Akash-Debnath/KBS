<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExchangeRateController extends Controller
{


        
    /**
     * @var exchangeRateService
     */
    protected $exchangeRateService;

    /**
     * CategoryController constructor.
     * @param ExchangeRateService $exchangeRateService
     *
     */
    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->can('menu-list')) {
    
            return $this->exchangeRateService->paginateData($request);
        } else {
            return response()->json([
                'status_code' => 424,
                'messages'=>'User does not have the right permissions'
            ]);
        }
        // return $this->exchangeRateService->paginateData($request);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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

            return $this->exchangeRateService->createItem($request);

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
     * @param  \App\Models\ExchangeRate  $exchangeRate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::user()->can('menu-list')) {

            return $this->exchangeRateService->getById($id);

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
     * @param  \App\Models\ExchangeRate  $exchangeRate
     * @return \Illuminate\Http\Response
     */
    public function edit(ExchangeRate $exchangeRate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExchangeRate  $exchangeRate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(Auth::user()->can('menu-edit')) {

            return $this->exchangeRateService->updateItem($request);

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
     * @param  \App\Models\ExchangeRate  $exchangeRate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(Auth::user()->can('menu-delete')) {

            return  $this->exchangeRateService->deleteItem($request->id);

        } else {

            return response()->json([
                'status_code' => 424,
                'messages'=>'User does not have the right permissions'
            ]);
        }
    }

    public function getExchangeRateList()
    {
            return  $this->exchangeRateService->getAll();
    }
}
