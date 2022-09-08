<?php


namespace App\Services;


use Auth;
use Exception;
use App\Models\Media;
use App\Helpers\Helper;
use App\Models\Category;
use App\Models\CategoryGroups;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Validator;

class CategoryService
{

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;


    /**
     * CategoryService constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    /**
     * @return JsonResponse
     */
    public function getAll()
    {

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'category_list' => $this->categoryRepository->all()
        ]);
    }

    public function getLatestCategory(){
        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'category_info' => $this->categoryRepository->latestCategory()
        ]);
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {

        if($this->categoryRepository->get($id))
            return response()->json([
                'status_code' => 200,
                'messages'    => config('status.status_code.200'),
                'category_info' => $this->categoryRepository->get($id)
            ]);

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'category_info' => "Data not found"
        ]);

    }


    /**
     * @param $request
     * @return JsonResponse
     */

    public function getCategoryNameExist($request){
        if (!empty($request->id)){
            $validator = Validator::make($request->all(),[
                // 'name' => "required|min:3|max:100|unique:categories,name,$request->id,id",
                'name' => ['required','min:3','max:100',Rule::unique('categories')->where(function ($query) use ($request) {
                    return $query->where('id','!=',$request->id);
                })],
            ]);
        }
        else{
            $validator = Validator::make($request->all(),[
                'name' => "required|min:3|max:100|unique:categories,name",
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

            'name' => "required|min:3|max:100|unique:categories,name",

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

            // $this->categoryRepository->create($input);
            
            $result_array = explode(",",$request->role_id);
            
            if (in_array('1',$result_array)){
                $this->categoryRepository->create($input);
            }else{
                array_push($result_array,1);
                $request->role_id = implode(',',$result_array);
                $this->categoryRepository->create($input);
            }
            $category = $this->categoryRepository->get($input['id']);

//            $groups = json_decode($input['group_list'], TRUE);
//
//            if (count($groups)> 0){
//                foreach ($groups as $group){
//                    CategoryGroups::create([
//                        'category_id' => $input['id'],
//                        'group_id' => $group['id']
//                    ]);
//                }
//            }

            $category->history()->create([
                'user_id'           => $input['user_id'],
                'post_id'           => $input['id'],
                'operation_type'    => $request->route()->getActionMethod()
            ]);

            if($request->hasFile('logo')) {

                $logo_url  = Helper::base64PageLogoUpload("category/images", $request->logo);

                $category->media()->create([

                    'url' => $logo_url

                ]);
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
            //'name' => "required|min:3|max:100|unique:categories,name,$request->id,id",
            'name' => ['required','min:3','max:100',Rule::unique('categories')->where(function ($query) use ($request) {
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


            $category = $this->categoryRepository->get($input['id']);



            $result_array = explode(",",$input['role_id']);

            if (in_array('1', $result_array)){
                $input['role_id'] =  implode(',',$result_array);
                $this->categoryRepository->update([
                    'name' => $request->input('name'),
                    'slug' => Helper::slugify($request->input('name')),
                    'parent_id' => $request->input('parent_id') ?? 0,
                    'role_id' => $input['role_id']
                ], $request->id);
            }else{
                array_push($result_array,1);
                $input['role_id'] = implode(',',$result_array);
                $this->categoryRepository->update([
                    'name' => $request->input('name'),
                    'slug' => Helper::slugify($request->input('name')),
                    'parent_id' => $request->input('parent_id') ?? 0,
                    'role_id' => $input['role_id']
                ], $request->id);
            }

//            DB::table('category_groups')->where('category_id', $input['id'])->delete();

//            $groups = json_decode($input['group_list'], TRUE);
//
//            if (count($groups)> 0){
//                foreach ($groups as $group){
//                    CategoryGroups::create([
//                        'category_id' => $input['id'],
//                        'group_id' => $group['id']
//                    ]);
//                }
//            }

            $category->history()->create([
                'user_id' => Auth::user()->id,
                'post_id' => $request->id,
                'operation_type' => $request->route()->getActionMethod()
            ]);

            if($request->hasFile('logo')) {


                $media= Media::where('mediable_id', $input['id'])->first();

                if ($media){
                    $mediaName =  strtolower(substr($media->url, strpos($media->url, "media") ));

                    if(file_exists($mediaName)){
                        unlink(public_path().'/'.$mediaName );
                    }
                    $media->delete();
                }

                $logo_url  = Helper::base64PageLogoUpload("category/images", $request->logo);

                $category->media()->create([

                    'url' => $logo_url

                ]);
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
            'messages'    => "Category Updated Successfully"
        ]);

    }


    public function deleteItem($id)
    {
        DB::beginTransaction();

        try {

            $childCheck = Category::where('parent_id', $id)->get();

            if ($childCheck->isNotEmpty()){

                return response()->json([
                    'status_code' => 400,
                    'messages'    => config('status.status_code.400'),
                    'error'       => 'Please delete the child category first'
                ]);

            }else{
                $category = $this->categoryRepository->get($id);


                DB::table('category_groups')->where('category_id', $id)->delete();


                $category->history()->create([
                    'user_id' => Auth::user()->id,
                    'post_id' => $id,
                    'operation_type' => 'delete'
                ]);
                $this->categoryRepository->delete($id);
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
            'messages'    => "Category Deleted Successfully"
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
            'category_list' => $this->categoryRepository->getWithPagination($request)]);

    }


    public function categoryArticles()
    {

        return response()->json([
            'status_code'   => 200,
            'messages'      => config('status.status_code.200'),
            'category_list' => $this->categoryRepository->categoryArticles()]);

    }

    public function getCategoryListForUpdate($request)
    {

        return response()->json([
            'status_code'   => 200,
            'messages'      => config('status.status_code.200'),
            'category_list' => $this->categoryRepository->categoryListForUpdate($request)]);

    }
}
