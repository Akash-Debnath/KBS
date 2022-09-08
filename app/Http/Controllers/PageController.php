<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PageService;
use Auth;

class PageController extends Controller
{
    private $pageService;

    /**
     * PermissionController constructor.
     * @param PageService $pageService
     */

    public function __construct(PageService $pageService)
    {

        $this->pageService = $pageService;

    }

    public function index()
    {
        return $this->pageService->getAll();
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        
        if(Auth::user()->can('page-create')) {

            return $this->pageService->createItem($request);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param Page $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        
        if(Auth::user()->can('page-list')) {

            return $this->pageService->getById($id);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }

    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Page $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        
        if(Auth::user()->can('page-edit')) {

            return $this->pageService->updateItem($request);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Page $page
     * @return Response
     */
    public function destroy(Page $page)
    {
        //
    }
}
