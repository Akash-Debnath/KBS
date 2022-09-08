<?php

namespace App\Http\Controllers;

use App\Models\DynamicPage;
use App\Services\DynamicPageService;
use Illuminate\Http\Request;
use Auth;
class DynamicPageController extends Controller
{   
    private $dynamicPageService;

    /**
     * PermissionController constructor.
     * @param DynamicPageService $pageService
     */

    public function __construct(DynamicPageService $dynamicPageService)
    {

        $this->dynamicPageService = $dynamicPageService;

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $this->dynamicPageService->getAll();

        if(Auth::user()->can('menu-list')) {
    
            return $this->dynamicPageService->getAll();
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
        if(Auth::user()->can('page-create')) {

            return $this->dynamicPageService->createItem($request);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DynamicPage  $dynamicPage
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::user()->can('page-list')) {

            return $this->dynamicPageService->getById($id);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DynamicPage  $dynamicPage
     * @return \Illuminate\Http\Response
     */
    public function edit(DynamicPage $dynamicPage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DynamicPage  $dynamicPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(Auth::user()->can('page-edit')) {

            return $this->dynamicPageService->updateItem($request);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DynamicPage  $dynamicPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        if(Auth::user()->can('menu-delete')) {

            return  $this->dynamicPageService->deleteItem($request->id);

        } else {

            return response()->json([
                'status_code' => 424,
                'messages'=>'User does not have the right permissions'
            ]);
        }
    }

    
    public function checkPageTitleExist(Request $request)
    {

        return $this->dynamicPageService->checkPageTitleExist($request);

    }


    public function checkMenuNameExist(Request $request)
    {

        return $this->dynamicPageService->checkMenuNameExist($request);

    }
    
}
