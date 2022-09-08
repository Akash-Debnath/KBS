<?php


namespace App\Services;


use App\Helpers\Helper;
use App\Models\groupRoles;
use App\Repositories\GroupRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GroupService
{
    protected $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {

        $this->groupRepository             = $groupRepository;

    }

    public function listItems($request)
    {

        DB::beginTransaction();

        try{

            $listing                = $this->groupRepository->listing($request);

        }catch (Exception $e) {

            DB::rollBack();
            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status'            => 424,
                'messages'          => config('status.status_code.424'),
                'error'             => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status'                => 200,
            'messages'              => config('status.status_code.200'),
            'group_list'            => $listing
        ]);
    }

    public function createItem($request)
    {
//        return $request->all();
        $validator = Validator::make($request->all(),[

            'name'                  => 'required|string|min:3|max:100|unique:groups'

        ]);

        if($validator->fails()) {

            return response()->json([
                'status'            => 400,
                'messages'          => config('status.status_code.400'),
                'errors'            => $validator->errors()->all()
            ]);

        }

        $data                       = $request->all();
        $data['slug']               = Helper::slugify($request->name);

        DB::beginTransaction();

        try{

            $groupData              = $this->groupRepository->create($data);


            if (isset($data['role_list'])){

                if (count($data['role_list']) > 0){
                    foreach ($data['role_list'] as $role){
                        groupRoles::create([
                           'group_id' => $groupData->id,
                           'role_id' => $role['id']
                        ]);
                    }

                }

            }



        }catch (Exception $e) {

            DB::rollBack();
            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status'            => 424,
                'messages'          => config('status.status_code.424'),
                'error'             => $e->getMessage()
            ]);

        }

        DB::commit();

        return response()->json([
            'status'                => 201,
            'message'               => config('status.status_code.201'),
            'group_created'         => $groupData
        ]);
    }


    public function showItem($id)
    {

        DB::beginTransaction();

        try{

            $group_info                  = $this->groupRepository->show($id);

        }catch (Exception $e) {

            DB::rollBack();
            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status'            => 424,
                'messages'          => config('status.status_code.424'),
                'error'             => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status'                => 200,
            'message'               => config('status.status_code.200'),
            'group_info'            => $group_info
        ]);
    }

    public function updateItem($request,$id)
    {
//        return $request->all();
        $validator = Validator::make($request->all(),[

            'name'                  => "required|string|min:1|unique:groups,name,$id,id"

        ]);

        if($validator->fails()) {

            return response()->json([
                'status'            => 400,
                'messages'          => config('status.status_code.400'),
                'errors'            => $validator->errors()->all()
            ]);

        }

        $data                       = $request->all();


        if (isset($data['name'])){
            $data['slug']           = Helper::slugify($data['name']);
        }


        DB::beginTransaction();

        try{


            $this->groupRepository->update($data, $id);
            $group_info = $this->groupRepository->show($id);

            DB::table('group_roles')->where('group_id', $id)->delete();

            if (isset($data['role_list'])){
                if (count($data['role_list']) > 0){
                    foreach ($data['role_list'] as $role){
                        groupRoles::create([
                            'group_id' => $group_info->id,
                            'role_id' => $role['id']
                        ]);
                    }

                }

            }



        }catch (Exception $e) {

            DB::rollBack();
            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status'            => 424,
                'messages'          => config('status.status_code.424'),
                'error'             => $e->getMessage()
            ]);

        }

        DB::commit();

        return response()->json([
            'status'                => 200,
            'message'               => config('status.status_code.200'),
            'group_info'         => $group_info
        ]);
    }


    public function deleteItem($id)
    {

        DB::beginTransaction();

        try{

            DB::table('group_roles')->where('group_id', $id)->delete();

            $this->groupRepository->delete($id);

        }catch (Exception $e) {

            DB::rollBack();

            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status'            => 424,
                'messages'          => config('status.status_code.424'),
                'error'             => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status'                => 200,
            'message'               => config('status.status_code.200'),
        ]);

    }
}