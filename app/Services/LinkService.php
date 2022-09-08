<?php


namespace App\Services;


use App\Helpers\Helper;
use App\Models\Link;
use App\Models\Media;
use App\Repositories\LinkRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
// use Auth;
use Illuminate\Support\Facades\Auth;

class LinkService
{

    /**
     * @var LinkRepository
     */
    protected $linkRepository;


    /**
     * MenuService constructor.
     * @param LinkRepository $linkRepository
     */
    public function __construct(LinkRepository $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }


    
    /**
     * @return JsonResponse
     */
    public function getAll()
    {

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'link_list' => $this->linkRepository->all()
        ]);
    }

   

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {

        if($this->linkRepository->get($id))
            return response()->json([
                'status_code' => 200,
                'messages'    => config('status.status_code.200'),
                'link_info' => $this->linkRepository->get($id)
            ]);

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'link_info' => "Data not found"
        ]);

    }


    public function createItem($request)
    {
        $validator = Validator::make($request->all(),[

            'title' => "required|min:1|max:50|unique:links,title",
            'url' => "required|min:1",

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

            $this->linkRepository->create($input);  

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
            'title' => ['required','min:1','max:50',Rule::unique('links')->where(function ($query) use ($request) {
                return $query->where('id','!=',$request->id);
            })],

            'url'   => "required|min:1",
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

            $menu = $this->linkRepository->get($input['id']);

            $this->linkRepository->update( $input, $request->id);



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
            'messages'    => "Link Updated Successfully"
        ]);

    }


    public function deleteItem($id)
    {
        DB::beginTransaction();

        try {

            $this->linkRepository->delete($id);

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
            'messages'    => "Link Deleted Successfully"
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
            'link_list' => $this->linkRepository->getWithPagination($request)]);

    }

}