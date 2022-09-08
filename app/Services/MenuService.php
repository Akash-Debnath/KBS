<?php


namespace App\Services;


use App\Helpers\Helper;
use App\Models\Menu;
use App\Models\CategoryGroups;
use App\Models\Media;
use App\Repositories\MenuRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
// use Auth;
use Illuminate\Support\Facades\Auth;

class MenuService
{

    /**
     * @var MenuRepository
     */
    protected $menuRepository;


    /**
     * MenuService constructor.
     * @param MenuRepository $menuRepository
     */
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }


    /**
     * @return JsonResponse
     */
    public function getAll()
    {

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'menu_list' => $this->menuRepository->all()
        ]);
    }

   

    /**
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {

        if($this->menuRepository->get($id))
            return response()->json([
                'status_code' => 200,
                'messages'    => config('status.status_code.200'),
                'menu_info' => $this->menuRepository->get($id)
            ]);

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'menu_info' => "Data not found"
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

            'name' => "required|min:3|max:100|unique:menus,name",
            'order_number' => ['required','min:1','max:100',Rule::unique('menus')->where(function ($query) use ($request) {
                return $query->where('id','!=',$request->id);
            })],

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

            $this->menuRepository->create($input);
            $menu = $this->menuRepository->get($input['id']);

           

            $menu->history()->create([
                'user_id'           => $input['user_id'],
                'post_id'           => $input['id'],
                'operation_type'    => $request->route()->getActionMethod()
            ]);

            // if($request->hasFile('logo')) {

            //     $logo_url  = Helper::base64PageLogoUpload("category/images", $request->logo);

            //     $category->media()->create([

            //         'url' => $logo_url

            //     ]);
            // }

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

            //   return $request->all();

        $validator = Validator::make($request->all(),[
            'name' => ['required','min:3','max:100',Rule::unique('menus')->where(function ($query) use ($request) {
                return $query->where('id', '!=',$request->id);
            })],
            'order_number' => ['required','min:1','max:100',Rule::unique('menus')->where(function ($query) use ($request) {
                return $query->where('id', '!=',$request->id);
            })],
            
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

            $input['slug'] = Helper::slugify($input['name']);

            $menu = $this->menuRepository->get($input['id']);

            $this->menuRepository->update( $input, $request->id);

            $menu->history()->create([
                'user_id' => Auth::user()->id,
                'post_id' => $request->id,
                'operation_type' => $request->route()->getActionMethod()
            ]);



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
            'messages'    => "Menu Updated Successfully"
        ]);

    }


    public function deleteItem($id)
    {
        DB::beginTransaction();

        try {

            $childCheck = Menu::where('parent_id', $id)->get();

            if ($childCheck->isNotEmpty()){

                return response()->json([
                    'status_code' => 400,
                    'messages'    => config('status.status_code.400'),
                    'error'       => 'Please delete the child menu first'
                ]);

            }else{
                $menu = $this->menuRepository->get($id);


            
                $menu->history()->create([
                    'user_id' => Auth::user()->id,
                    'post_id' => $id,
                    'operation_type' => 'delete'
                ]);
                $this->menuRepository->delete($id);
            }

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
            'messages'    => "Menu Deleted Successfully"
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
            'menu_list' => $this->menuRepository->getWithPagination($request)]);

    }

    public function getAllMenu()
    {

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'menu_list' => $this->menuRepository->allMenu()
        ]);
    }

    public function getMenuPageData($slug)
    {

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'menu_page_data' => $this->menuRepository->menuPageData($slug)
        ]);
    }

}
