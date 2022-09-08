<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * UserController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {

        // $this->middleware('acl:super-admin');
        $this->userService = $userService;

    }


    /**
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return $this->userService->paginateData($request);

    }

    /**
     * @return void
     */
    public function create()
    {

        /*$roles = Role::pluck('name', 'name')->all();

        return view('users.create', compact('roles'));*/

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
//        return $request->all();
        return $this->userService->createItem($request);

    }


    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {

        return $this->userService->getById($id);

    }

    /**
     * @param $id
     * @return void
     */
    public function edit($id)
    {

       //

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {

        return $this->userService->updateItem($request);

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {

        return  $this->userService->deleteItem($request->id);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function changePassword(Request $request)
    {

        return $this->userService->updatePassword($request);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function checkUserNameExist(Request $request)
    {

        return $this->userService->getUserNameExist($request);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function checkUserEmailExist(Request $request)
    {

        return $this->userService->getUserEmailExist($request);

    }


    public function logout(Request $request) {

        if ($request->user()->token()->revoke()){
            return response()->json([
                'status_code'   => 200,
                'status'        => config('status.status_code.200'),
                'message'       => 'Logged out successfully!'
            ]);

        } else{
            return response()->json([
                'status_code'   => 200,
                'status'        => config('status.status_code.200'),
                'message'       => "Could not logout!"
            ]);
        }
    }
}

