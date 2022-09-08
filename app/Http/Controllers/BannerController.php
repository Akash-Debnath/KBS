<?php

namespace App\Http\Controllers;

use App\Services\BannerService;
use Illuminate\Http\Request;
use Auth;

class BannerController extends Controller
{
    protected $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->can('banner-list')) {

            return $this->bannerService->paginateData();

        }
        else{

            return response()->json([
                'status_code' => 424,
                'messages'=>'User does not have the right permissions'
            ]);

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return $request->all();

        if(Auth::user()->can('banner-create')) {

            return $this->bannerService->createItem($request);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->bannerService->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
//        return $request->all();

        if(Auth::user()->can('banner-edit')) {

            return $this->bannerService->updateItem($request);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        if(Auth::user()->can('banner-delete')) {

            return  $this->bannerService->deleteItem($request->id);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }

    }

    public function showLatestBannerList(Request $request){

        return  $this->bannerService->roleBanners($request);

//        if(Auth::user()->can('banner-list')) {
//
//            return  $this->bannerService->roleBanners($request);
//
//        } else {
//
//            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);
//
//        }
    }


    public function showHometBannerList(Request $request){

        return  $this->bannerService->homeBanners($request);

//        if(Auth::user()->can('banner-list')) {
//
//            return  $this->bannerService->roleBanners($request);
//
//        } else {
//
//            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);
//
//        }
    }
}
