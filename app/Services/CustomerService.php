<?php

namespace App\Services;

use Exception;
use http\Env\Request;
use App\Helpers\Helper;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Validator;


class CustomerService
{

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;


    /**
     * UserService constructor.
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {

        $this->customerRepository = $customerRepository;

    }


    /**
     * @return JsonResponse
     */
    


    /**
     * @param $id
     * @return JsonResponse
     */

    public function getUserNameExist($request){
        if (!empty($request->id)){
            $validator = Validator::make($request->all(),[
                // 'username'   => "required|string|max:50|min:3|unique:users,username,$request->id,id",
                'username' => ['required','min:3','max:50',Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('id','!=',$request->id);
                })],
            ]);
        }
        else{
            $validator = Validator::make($request->all(),[
                'username'   => 'unique:users',
            ]);
        }


        if($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'error'      => $validator->errors()->first()
            ]);
        }
    }

    public function getUserEmailExist($request){
        if (!empty($request->id)){
            $validator = Validator::make($request->all(),[
                // 'email'      => "required|unique:users,email,$request->id,id",
                'email' => ['required',Rule::unique('users')->where(function ($query) use ($request) {
                    return $query->where('id','!=',$request->id);
                })],
                
            ]);
        }else{
            $validator = Validator::make($request->all(),[
                'email'   => 'unique:users',
            ]);
        }

        if($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'error'      => $validator->errors()->first()
            ]);
        } 
    }



    /**
     * @param $request
     * @return JsonResponse
     */
    public function createItem($request)
    {

        $validator = Validator::make($request->all(),[

            'first_name' => 'required|string|min:3|max:100',
            'last_name'  => 'required|string|min:3|max:100',
            'username'   => 'required|string|max:50|min:3|unique:users',
            'email'      => 'required|email|max:60|unique:users',
            'password'   => 'required|same:confirm_password',
        ]);

        if($validator->fails()) {

           return response()->json([
               'status_code' => 400,
               'messages'    => config('status.status_code.400'),
               'errors'      => $validator->errors()->all()
           ]);

        }

        if(isset($request->image_file)) {

            $thumbFile = Helper::base64ProfileImageThumbUpload("avatar", $request->image_file);

        } else {

            $thumbFile = url('/').'/media/avatar/placeholder.png';
        }


        $input = $request->all();
        $input['id'] = time().rand(1000,9000);
        $input['password'] = Hash::make($request->password);

        DB::beginTransaction();

        try {

            $this->customerRepository->create($input);
            $user = $this->getItemById($input['id']);

            $user->roles()->attach($request->input('roles'));

            $user->media()->create([

                'url' => $thumbFile

            ]);

            if(!empty($request->quizId) && !empty($request->quizScore)) {
                $this->customerRepository->createResult( $input['id'],$request);
            }

        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status_code' => 424,
                'messages'=>config('status.status_code.424'),
                'error' => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status_code' => 201,
            'messages'=>config('status.status_code.201')
        ]);


    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function updateItem($request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|string|min:3|max:100',
            'last_name'  => 'required|string|min:3|max:100',
            'username' => ['required','min:3','max:50',Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('id','!=',$request->id);
            })],
            'email' => ['required',Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('id','!=',$request->id);
            })],
            'roles'      => 'required'
        ]);

        if($validator->fails()) { 

            return response()->json([
                'status_code' => 400,
                'messages' => config('status.status_code.400'),
                'errors' =>  $validator->errors()->all()
            ]);

        }

        $input = $request->all();

        $input['slug']   = Helper::slugify($request->first_name.$request->last_name) ;
        $input['status'] = $request->status ?? 1;

        if(!empty($input['password'])) {

            $input['password'] = Hash::make($input['password']);

        } else {

            $input = Arr::except($input,array('password'));

        }

        DB::beginTransaction();

        try {

            $this->customerRepository->update($input, $input['id']);
            $user = $this->getItemById($input['id']);

            DB::table('users_roles')->where('user_id', $input['id'])->delete();
            DB::table('users_permissions')->where('user_id', $input['id'])->delete();

            $user->roles()->attach($request->input('roles'));


        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status_code' => 424,
                'messages'=>config('status.status_code.424'),
                'error' => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status_code' => 200,
            'messages'=>config('status.status_code.200')
        ]);

    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteItem($id)
    {
        DB::beginTransaction();

        try {

            $this->customerRepository->delete($id);

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json(['status_code' => 424, 'messages'=>config('status.status_code.424'), 'error' => $e->getMessage()]);
        }

        DB::commit();

        return response()->json(['status_code' => 200, 'messages'=>config('status.status_code.200')]);
    }

    public function updatePassword($request)
    {
        $validator = Validator::make($request->all(),[
            'password' => 'required|between:8,32|same:confirm_password|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\d)(?=.*[$%&_+-.@#]).+$/',

        ]);

        if($validator->fails()) {

            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'errors'      => $validator->errors()->all()
            ]);

        }

        $input['password'] = Hash::make($request->password);

        DB::beginTransaction();

        try {

            $this->customerRepository->update($input, $request->id);

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status_code'   => 424,
                'messages'      => config('status.status_code.424'),
                'error'         => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json(['status_code' => 200, 'messages'=>config('status.status_code.200')]);
    }


    /**
     * @return JsonResponse
     */
    

    /**
     * @param $id
     * @return mixed
     */
    public function getItemById($id)
    {

        return $this->customerRepository->getUser($id);
    }

}
