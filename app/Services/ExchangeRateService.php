<?php


namespace App\Services;


use App\Helpers\Helper;
use App\Models\Link;
use App\Models\Media;
use App\Repositories\ExchangeRateRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ExchangeRateService
{

    /**
     * @var exchangeRateRepository
     */
    protected $exchangeRateRepository;


    /**
     * MenuService constructor.
     * @param ExchangeRateRepository $exchangeRateRepository
     */
    public function __construct(ExchangeRateRepository $exchangeRateRepository)
    {
        $this->exchangeRateRepository = $exchangeRateRepository;
    }


    
    /**
     * @return JsonResponse
     */
    public function getAll()
    {

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'exchange_rate_list' => $this->exchangeRateRepository->all()
        ]);
    }

   

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {

        if($this->exchangeRateRepository->get($id))
            return response()->json([
                'status_code' => 200,
                'messages'    => config('status.status_code.200'),
                'rate_info' => $this->exchangeRateRepository->get($id)
            ]);

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'rate_info' => "Data not found"
        ]);

    }



    public function createItem($request)
    {
        $validator = Validator::make($request->all(),[
            'currency_name' => "required|min:1",
            'currency_code' => "required|min:1|max:20|unique:exchange_rates,currency_code",
            'buying_rate' => "required|min:1|max:100",
            'selling_rate' => "required|min:1|max:100",

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

            $this->exchangeRateRepository->create($input);  

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

        $validator = Validator::make($request->all(),[
            'currency_name' => "required|min:1",
            'currency_code' => ['required','min:1','max:20',Rule::unique('exchange_rates')->where(function ($query) use ($request) {
                return $query->where('id','!=',$request->id);
            })],
            'buying_rate' => "required|min:1",
            'selling_rate' => "required|min:1",
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

            $menu = $this->exchangeRateRepository->get($input['id']);

            $this->exchangeRateRepository->update( $input, $request->id);



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

            $this->exchangeRateRepository->delete($id);

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
            'messages'    => "Exchange Rate Deleted Successfully"
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
            'exchange_rate' => $this->exchangeRateRepository->getWithPagination($request)]);
    }

}