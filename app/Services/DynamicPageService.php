<?php


namespace App\Services;

use App\Helpers\Helper;
use App\Models\DynamicPageFile;
use App\Models\Page;
use App\Repositories\DynamicPageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class DynamicPageService
{

    /**
     * @var $dynamicPageRepository
     */
    protected $dynamicPageRepository;


    /**
     * QuizFormService constructor.
     * @param DynamicPageRepository $dynamicPageRepository
     */
    public function __construct(DynamicPageRepository $dynamicPageRepository)
    {

        $this->dynamicPageRepository = $dynamicPageRepository;

    }


    /**
     * @return JsonResponse
     */
    public function getAll()
    {
        return response()->json([
            'status_code'      => 200,
            'messages'         => config('status.status_code.200'),
            'page_list' => $this->dynamicPageRepository->all()
        ]);
    }





    public function getItemById($id)
    {
        return $this->dynamicPageRepository->get($id);
    }



    /**
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {

        if($this->dynamicPageRepository->get($id))
            return response()->json([
                'status_code' => 200,
                'messages'    => config('status.status_code.200'),
                'page_info' => $this->dynamicPageRepository->get($id),
            ]);

        return response()->json([
            'status_code' => 200,
            'messages'    => config('status.status_code.200'),
            'page_info'   => "Data not found"
        ]);

    }




    /**
     * @param $request
     * @return JsonResponse
     */
    public function createItem($request)
    {
        $thumbFile = [];

        $validator = Validator::make($request->all(),[
            'title' => 'required|unique:dynamic_pages,title',
            'menu_id' => 'required|unique:dynamic_pages,menu_id'
        ]);

        if($validator->fails()) {

            return response()->json([
                'status_code' => '400',
                'messages'=>config('status.status_code.400'),
                'error' =>  $validator->errors()->first()
            ]);

        }

        $input = $request->all();
        $input['id'] = time().rand(1000,9000);

        if($request->hasFile('banner_img')) {
            $input['banner_img']  = Helper::base64PageLogoUpload("page/banner_images", $request->banner_img);
        }

        $this->dynamicPageRepository->create($input);

        $thumbImageList = $request->file_name;

        if(isset($request->file_name) && count($request->file_name)>0) {

            if (isset($request->file_name)) {

                $fileLength = count($thumbImageList);

                if (count($thumbImageList) > 0) {
                    for ($i = 1; $i <= $fileLength; $i++) {
                        $thumbFile[] = Helper::fileUpload("page/files", $thumbImageList[count($thumbImageList) - $i]);
                    }
                }
            }


            foreach($thumbFile as $file) {

                $this->dynamicPageRepository->saveFiles($file,$input['id']);

            }
        }

        return response()->json(['status_code' => 200, 'messages'=>config('status.status_code.200')]);
    }


    /**
     * @param $request
     * @param $id
     * @return JsonResponse
     */
    public function updateItem($request)
    {

        $validator = Validator::make($request->all(),[
            'title' => ['required','min:3',Rule::unique('dynamic_pages')->where(function ($query) use ($request) {
                return $query->where('id', '!=',$request->id);
            })],
            'menu_id' => ['required',Rule::unique('dynamic_pages')->where(function ($query) use ($request) {
                return $query->where('id', '!=',$request->id);
            })],
        ]);

        if($validator->fails()) {

            return response()->json([
                'status_code' => '400',
                'messages'=>config('status.status_code.400'),
                'error' =>  $validator->errors()->first()
            ]);

        }

        DB::beginTransaction();

        try {

            $input = $request->all();

            // $config_data =  Page::find($request->id);

            if( $request->banner_url) {
               
                $input['banner_img']  = Helper::base64PageLogoUpload("page/banner_images", $request->banner_url);
            }

            $this->dynamicPageRepository->update($input, $request->id);


            $uploaded_previous_file = json_decode($request->previous_file_list);


            if (empty($uploaded_previous_file)){
                $mediaList = DynamicPageFile::where('dynamic_page_id', $request->id)->get();

                if (count($mediaList) > 0){
                    foreach ($mediaList as $media){
                        $mediaName = substr($media->file_name, strpos($media->file_name, "media"));
                        unlink(public_path().'/'.$mediaName);
                        DynamicPageFile::find($media->id)->delete();
                    }
                }
            }

            if (isset($uploaded_previous_file) && count($uploaded_previous_file) > 0){
                $previousFileIds = array_column($uploaded_previous_file, 'id');
                $previousMediaList = DynamicPageFile::where('dynamic_page_id', $request->id)->get();
                $previousMediaListIdArray = $previousMediaList->pluck('id')->toArray();

                $previousFileDiff = array_diff($previousMediaListIdArray,$previousFileIds);

                if ($previousFileDiff){
                    foreach($previousFileDiff as $aPreviousMedia)
                    {
                        $media = DynamicPageFile::where('id', $aPreviousMedia)->first();
                        $mediaName =  substr($media->file_name, strpos($media->file_name, "media") );
                        unlink(public_path().'/'.$mediaName );
                        DynamicPageFile::find($aPreviousMedia)->delete();
                    }
                }
            }

            if(isset($request->file_name) && count($request->file_name)>0) {

                $thumbImageList = $request->file_name;

                $fileLength = count($thumbImageList);

                if(count($thumbImageList) > 0) {
                    for ($i = 1; $i <= $fileLength; $i++) {

                        $thumbFile[] = Helper::fileUpload("page/files", $thumbImageList[count($thumbImageList) - $i]);

                    }
                }

                foreach ($thumbFile as $file)
                {
                    $this->dynamicPageRepository->saveFiles($file, $input['id']);
                }

               
            }

        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json([
                'status_code' => '424',
                'messages'=>config('status.status_code.424'),
                'error' => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json(['status_code' => 200, 'messages'=>config('status.status_code.200')]);

    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteItem($id)
    {

        DB::beginTransaction();

        try {

            $this->dynamicPageRepository->delete($id);

        } catch (Exception $e) {

            DB::rollBack();

            Log::error('Found Exception: ' . $e->getMessage() . ' [Script: ' . __CLASS__ . '@' . __FUNCTION__ . '] [Origin: ' . $e->getFile() . '-' . $e->getLine() . ']');

            return response()->json(['status_code' => '424',
                'messages'=>config('status.status_code.424'),
                'error' => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json(['status_code' => 200, 'messages'=>config('status.status_code.200')]);

    }


    public function checkPageTitleExist($request){
        if (!empty($request->id)){
            $validator = Validator::make($request->all(),[
                'title' => ['required','min:3','max:100',Rule::unique('dynamic_pages')->where(function ($query) use ($request) {
                    return $query->where('id','!=',$request->id);
                })],

            ]);
        }
        else{
            $validator = Validator::make($request->all(),[
                'title' => "required|min:3|max:100|unique:dynamic_pages,title",

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





    public function checkMenuNameExist($request){
        if (!empty($request->id)){
            $validator = Validator::make($request->all(),[
                'menu_id' => ['required','min:3','max:100',Rule::unique('dynamic_pages')->where(function ($query) use ($request) {
                    return $query->where('id','!=',$request->id);
                })],

            ]);
        }
        else{
            $validator = Validator::make($request->all(),[
                'menu_id' => "required|min:3|max:100|unique:dynamic_pages,menu_id",

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

}
