<?php


namespace App\Services;


use App\Helpers\Helper;
use App\Repositories\EmailSettingRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class EmailSettingService
{

    /**
     * @var EmailSettingRepository
     */
    protected $emailSettingRepository;


    /**
     * RoleService constructor.
     * @param EmailSettingRepository $roleRepository
     */
    public function __construct(EmailSettingRepository $emailSettingRepository)
    {
        $this->emailSettingRepository = $emailSettingRepository;
    }


    /**
     * @return JsonResponse
     */
    public function getAll()
    {
        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'email_config_info'   => $this->emailSettingRepository->all()
        ]);
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {

        if($this->emailSettingRepository->get($id))
            return response()->json([
                'status_code' => 200,
                'messages'    => config('status.status_code.200'),
                'role_info'   => $this->emailSettingRepository->get($id)
            ]);

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'role_info'   => "Data not found"
        ]);

    }


    /**
     * @param $request
     * @return JsonResponse
     */
   

    public function createItem($request)
    {
        $validator = Validator::make($request->all(),[
            'type' => 'required',
            'host' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password' => 'required',
            'primary_email' => 'required',
            
        ]);

        if($validator->fails()) {

            return response()->json([
                'status_code' => '400',
                'messages'=>config('status.status_code.400'),
                'error' =>  $validator->errors()->first()
            ]);

        }

        $input = $request->all();
        // $input['id'] = time().rand(1000,9000);

        $this->emailSettingRepository->create($input);

        return response()->json(['status_code' => 200, 'messages'=>config('status.status_code.200')]);
    }


    /**
     * @param $request
     * @return JsonResponse
     */
    public function updateItem($request)
    {

        $validator = Validator::make($request->all(),[
            'type' => 'required',
            'host' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password' => 'required',
            'primary_email' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json([
                'status_code' => '400',
                'messages'=>config('status.status_code.400'),
                'error' =>  $validator->errors()->first()
            ]);

        }

        $input = $request->all();
        $this->emailSettingRepository->update($input, $request->id);
        return response()->json(['status_code' => 200, 'messages'=>config('status.status_code.200')]);

    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteItem($id)
    {
       

    }


    /**
     * @return JsonResponse
     */

}
