<?php

namespace App\Http\Controllers;

use App\Services\LinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{

    
    /**
     * @var LinkService
     */
    protected $linkService;

    /**
     * CategoryController constructor.
     * @param LinkService $linkService
     *
     */
    public function __construct(LinkService $linkService)
    {
        $this->linkService = $linkService;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->can('menu-list')) {
    
            return $this->linkService->paginateData($request);
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

            return $this->linkService->createItem($request);

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
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::user()->can('menu-list')) {

            return $this->linkService->getById($id);

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
     * @param  \App\Models\Link  $link
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
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   

        if(Auth::user()->can('menu-edit')) {

            return $this->linkService->updateItem($request);

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
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(Auth::user()->can('menu-delete')) {

            return  $this->linkService->deleteItem($request->id);

        } else {

            return response()->json([
                'status_code' => 424,
                'messages'=>'User does not have the right permissions'
            ]);
        }
    }


    public function checkMenuNameExist(Request $request)
    {

        return $this->linkService->getMenuNameExist($request);

    }


    public function getAllUsefulLinks()
    {

        return $this->linkService->getAll();

    }


    
}
