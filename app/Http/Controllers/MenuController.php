<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Services\MenuService;
use Auth;
class MenuController extends Controller
{
  

    /**
     * @var MenuService
     */
    protected $menuService;

    /**
     * CategoryController constructor.
     * @param MenuService $categoryService
     *
     */
    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * @return mixed
     */

    public function index(Request $request)
    {   
        
        if(Auth::user()->can('menu-list')) {
    
            return $this->menuService->paginateData($request);
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
        //
        if(Auth::user()->can('menu-create')) {

            return $this->menuService->createItem($request);

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
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if(Auth::user()->can('menu-list')) {

            return $this->menuService->getById($id);

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
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        if(Auth::user()->can('menu-edit')) {

            return $this->menuService->updateItem($request);

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
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        if(Auth::user()->can('menu-delete')) {

            return  $this->menuService->deleteItem($request->id);

        } else {

            return response()->json([
                'status_code' => 424,
                'messages'=>'User does not have the right permissions'
            ]);
        }
    }

    public function checkMenuNameExist(Request $request)
    {

        return $this->menuService->getMenuNameExist($request);

    }

    public function getAllMenu()
    {   
        
        if(Auth::user()->can('menu-list')) {
    
            return $this->menuService->getAll();
        } else {
            return response()->json([
                'status_code' => 424,
                'messages'=>'User does not have the right permissions'
            ]);
        }

    }


    public function getAllMenuNavbars()
    {   
        
        return $this->menuService->getAllMenu();

    }

    public function getMenuPageData($slug)
    {   
        
        return $this->menuService->getMenuPageData($slug);

    }

}
