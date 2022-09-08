<?php


namespace App\Services;


use App\Helpers\Helper;
use App\Models\Banner;
use App\Models\Media;
use App\Repositories\BannerRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BannerService
{

    /**
     * @var CategoryRepository
     */
    protected $bannerRepository;


    /**
     * CategoryService constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    public function paginateData()
    {
        return response()->json([
            'status_code'   => 200,
            'messages'      => config('status.status_code.200'),
            'banner_list' => $this->bannerRepository->getWithPagination()]);

    }

    public function createItem($request)
    {
        $validator = Validator::make($request->all(),[

            'title' => 'required|min:3|max:200',
            'banner_file' => 'nullable|mimes:jpeg,png,jpg,gif,svg,mp4,ogg|max:30720'

        ]);

        if($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'errors'      => $validator->errors()->all()
            ]);

        }

        $input = $request->all();
        $input['id'] = time().rand(1000,9000);
        $input['user_id'] = auth()->user()->id;

        DB::beginTransaction();

        try {

            $result_array = explode(",",$input['role_id']);

            if (in_array('1',$result_array)){
                $this->bannerRepository->create($input);
            }else{
                array_push($result_array,'1');
                $input['role_id'] = implode(',',$result_array);
                $this->bannerRepository->create($input);
            }


            $banner = $this->bannerRepository->get($input['id']);

            $banner->history()->create([
                'user_id'           => $input['user_id'],
                'post_id'           => $input['id'],
                'operation_type'    => $request->route()->getActionMethod()
            ]);

            if($request->hasFile('banner_file')) {

                $banner_url  = Helper::base64PageLogoUpload("banner/files", $request->banner_file);

                $banner->media()->create([

                    'url' => $banner_url

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


    public function getById($id){

        if ($this->bannerRepository->get($id)){
            return response()->json([
                'status_code'  => 200,
                'messages'     => config('status.status_code.200'),
                'banner_info' => $this->bannerRepository->get($id)
            ]);
        }else{
            return response()->json([
                'status_code'  => 200,
                'messages'     => config('status.status_code.200'),
                'banner_info' => "Data not found"
            ]);
        }
    }

    public function updateItem($request){

        $validator = Validator::make($request->all(),[
            'title' => 'required|min:3|max:200',
            'banner_file' => 'nullable|mimes:jpeg,png,jpg,gif,svg,mp4,ogg|max:30720'

        ]);

        if($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'errors'      => $validator->errors()->all()
            ]);

        }

        $input = $request->all();
        $input['user_id'] = auth()->user()->id;

        DB::beginTransaction();

        try {

            $result_array = explode(",",$input['role_id']);

            if (in_array('1',$result_array)){
                $this->bannerRepository->update($input);
            }else{
                array_push($result_array,'1');
                $input['role_id'] = implode(',',$result_array);
                $this->bannerRepository->update($input);
            }
            $banner = $this->bannerRepository->get($input['id']);


            $banner->history()->create([
                'user_id' => auth()->user()->id,
                'post_id' => $request->id,
                'operation_type' => $request->route()->getActionMethod()
            ]);

            if($request->hasFile('banner_file')) {

                $media= Media::where('mediable_id', $request->id)->first();

                if ($media){
                    $mediaName =  substr($media->url, strpos($media->url, "media") );
                    unlink(public_path().'/'.$mediaName );
                    $media->url = Helper::base64PageLogoUpload("banner/files", $request->banner_file);
                    $media->save();
                }else{
                    $banner = $this->bannerRepository->get($request->id);
                    $banner_url  = Helper::base64PageLogoUpload("banner/files", $request->banner_file);

                    $banner->media()->create([

                        'url' => $banner_url

                    ]);
                }
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
            'status_code' => 200,
            'messages'=>config('status.status_code.200'),
        ]);
    }

    public function getItemById($id)
    {

        return $this->bannerRepository->get($id);
    }

    public function deleteItem($id){

        DB::beginTransaction();


        try {
            $banner = $this->getItemById($id);

            $banner->history()->create([
                'user_id' => auth()->user()->id,
                'post_id' => $id,
                'operation_type' => 'delete'
            ]);
            $this->bannerRepository->delete($id);

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
            'messages'    => "Banner Deleted Successfully"

        ]);
    }


    public function roleBanners($request){

        return response()->json([

                'status_code'   => 200,
                'messages'      => config('status.status_code.200'),
                'banner_list' => $this->bannerRepository->getRoleBanners($request)

        ]);
    }


    public function homeBanners($request){

        return response()->json([

                'status_code'   => 200,
                'messages'      => config('status.status_code.200'),
                'banner_list' => $this->bannerRepository->geHomeBanners($request)

        ]);
    }

}