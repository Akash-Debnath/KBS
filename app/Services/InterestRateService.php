<?php


namespace App\Services;


use App\Helpers\Helper;
use App\Models\InterestRate;
use App\Models\Media;
use App\Repositories\InterestRateRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
// use Auth;
use Illuminate\Support\Facades\Auth;

class InterestRateService
{

    /**
     * @var InterestRateRepository
     */
    protected $interestRateRepository;


    /**
     * MenuService constructor.
     * @param LinkRepository $linkRepository
     */
    public function __construct(InterestRateRepository $interestRateRepository)
    {
        $this->interestRateRepository = $interestRateRepository;
    }


    
    /**
     * @return JsonResponse
     */
    public function getAll()
    {

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'interest_rate_list' => $this->interestRateRepository->all()
        ]);
    }

   

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {

        if($this->interestRateRepository->get($id))
            return response()->json([
                'status_code' => 200,
                'messages'    => config('status.status_code.200'),
                'rate_info' => $this->interestRateRepository->get($id)
            ]);

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'rate_info' => "Data not found"
        ]);

    }


    /**
     * @param $request
     * @return JsonResponse
     */

    public function getMenuNameExist($request){
        if (!empty($request->id)){
            $validator = Validator::make($request->all(),[
                'name' => ['required','min:3','max:100',Rule::unique('menus')->where(function ($query) use ($request) {
                    return $query->where('id','!=',$request->id);
                })],

            ]);
        }
        else{
            $validator = Validator::make($request->all(),[
                'name' => "required|min:3|max:100|unique:menus,name",

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


    public function createItem($request)
    {
        $validator = Validator::make($request->all(),[
            'title' => "required|min:3|max:100|unique:interest_rates,title",
            'rate' => "required|min:1|max:100",

            ]);

        if($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'errors'      => $validator->errors()->all()
            ]);

        }



        $input = $request->all();
        $input['id'] = Helper::idGenarator();
        $input['user_id'] = Auth::user()->id;


        DB::beginTransaction();

        try {

            $this->interestRateRepository->create($input);  

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status_code' => 424,
                'messages'    => config('status.status_code.424'),
                'error'       => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json(['status_code' => 200, 'messages'=>config('status.status_code.200')]);

    }


    /**
     * @param $request
     * @return JsonResponse
     */
    public function updateItem($request)
    {

//        return $request->all();

        $validator = Validator::make($request->all(),[
            'title' => ['required','min:3','max:100',Rule::unique('interest_rates')->where(function ($query) use ($request) {
                return $query->where('id','!=',$request->id);
            })],
            'rate'   => "required|min:1|max:100",
        ]);

        if($validator->fails()) {

            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'errors'      => $validator->errors()->all()
            ]);

        }

        $input = $request->all();

        DB::beginTransaction();

        try {

            $menu = $this->interestRateRepository->get($input['id']);

            $this->interestRateRepository->update( $input, $request->id);



        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status_code' => 424,
                'messages'    => config('status.status_code.424'),
                'error'       => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status_code' => 200,
            'messages'    => "Rate Updated Successfully"
        ]);

    }


    public function deleteItem($id)
    {
        DB::beginTransaction();

        try {

            $this->interestRateRepository->delete($id);

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status_code' => 424,
                'messages'    => config('status.status_code.424'),
                'error'       => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status_code' => 200,
            'messages'    => "Interest Rate Deleted Successfully"
        ]);

    }

    /**
     * @return JsonResponse
     */
    public function paginateData($request)
    {
        return response()->json([
            'status_code'   => 200,
            'messages'      => config('status.status_code.200'),
            'interest_rate' => $this->interestRateRepository->getWithPagination($request)]);

    }



}