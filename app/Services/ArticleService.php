<?php


namespace App\Services;

use Exception;
use App\Models\Media;
use App\Helpers\Helper;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\ArticleRepository;
use Illuminate\Support\Facades\Validator;

class ArticleService
{
    /**
     * @var ArticleRepository
     */
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {

        $this->articleRepository = $articleRepository;

    }

    /**
     * @return JsonResponse
     */
    public function getAll()
    {

        return response()->json([
            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'article_list' => $this->articleRepository->all()
        ]);

    }

    public function getLatestList($request)
    {
        $data = mb_convert_encoding($this->articleRepository->latestArticleList($request), "UTF-8", "auto");
        return response()->json([
            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'article_list' => $data
        ]);
    }

    public function getAllArticleList($request){
        return response()->json([
            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'article_list' => $this->articleRepository->allWithRole($request)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createItem(Request $request)
    {
        $thumbFile = [];

        $validator = Validator::make($request->all(),[
            'en_title'    => 'required|min:3|max:190',
            'category_id' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json([
                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'errors'      => $validator->errors()->all()
            ]);

        }

        $input = $request->all();

        $input['id'] = $request->id;
        $input['user_id'] = auth()->user()->id;
        $input['publish_date'] = date('Y-m-d H:i:s');


        $thumbImageList = $request->uploaded_file;

        DB::beginTransaction();

        try {

            if($request->hasFile('thumbnail_img')) {
                $input['thumbnail_url']  = Helper::base64PageLogoUpload("article/thumbnail_images", $request->thumbnail_img);
            }
            if($request->hasFile('banner_img')) {
                $input['banner_url']  = Helper::base64PageLogoUpload("article/banner_images", $request->banner_img);
            }

            $this->articleRepository->create($input);

            $article = $this->getItemById($input['id']);

            $article->history()->create([
                'user_id'           => $input['user_id'],
                'post_id'           => $input['id'],
                'operation_type'    => $request->route()->getActionMethod()
            ]);
            
           
            if(isset($request->uploaded_file) && count($request->uploaded_file)>0) {

                if (isset($request->uploaded_file)) {

                    $fileLength = count($thumbImageList);

                    if (count($thumbImageList) > 0) {
                        for ($i = 1; $i <= $fileLength; $i++) {
                        //  dd($thumbImageList[count($thumbImageList) - $i]);
                            $thumbFile[] = Helper::fileUpload("article/files", $thumbImageList[count($thumbImageList) - $i]);
                        }
                    }
                }

                foreach ($thumbFile as $file):

                    $article->media()->create([

                        'url' => $file

                    ]);

                endforeach;
            }

            //notification
            // $article_contents =  $this->articleRepository->getArticleContents($article->id);
            if($article->is_notifiable==1) {
                $articleNotification = $this->sendArticleNotification($article->id , 'add');
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
            'status_code' => 201,
            'messages'=>config('status.status_code.201')
        ]);

    }



    public function getItemById($id)
    {

        return $this->articleRepository->get($id);
    }

    /**
     * @param $id
     * @return JsonResponse
     */

    public function getById($id)
    {

        if($this->articleRepository->get($id))

            return response()->json([
                'status_code'  => 200,
                'messages'     => config('status.status_code.200'),
                'article_info' => $this->articleRepository->get($id)
            ]);

        return response()->json([
            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'article_info' => "Data not found"
        ]);

    }

    public function getArticleDetailsBySlug($slug)
    {

        if($this->articleRepository->getBySlug($slug))

            return response()->json([
                'status_code'  => 200,
                'messages'     => config('status.status_code.200'),
                'article_info' => $this->articleRepository->getBySlug($slug)
            ]);

        return response()->json([
            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'article_info' => "Data not found"
        ]);

    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function updateItem($request)
    {

        // return $request->all();
        $validator = Validator::make($request->all(),[

            'en_title'    => 'required|string',
            'category_id' => 'required',

        ]);

        if($validator->fails()) {

            return response()->json([

                'status_code' => 400,
                'messages'    => config('status.status_code.400'),
                'errors'      => $validator->errors()->all()

            ]);

        }

        $input          = $request->all();
        $randomString   = Str::random();
        $input['slug']  = Helper::slugify($input['en_title']).$randomString;

        DB::beginTransaction();

        try {

            if($request->thumbnail_url) {
    
                $input['thumbnail_img']  = Helper::base64PageLogoUpload("article/thumbnail_images", $request->thumbnail_url);
            }
            if( $request->banner_url) {
                $input['banner_img']  = Helper::base64PageLogoUpload("article/banner_images", $request->banner_url);
            }

            $this->articleRepository->update($input, $input['id']);

            $article = $this->articleRepository->get($input['id']);


            $article->history()->create([
                'user_id' => auth()->user()->id,
                'post_id' => $request->id,
                'operation_type' => $request->route()->getActionMethod()
            ]);

            $uploaded_previous_file = json_decode($request->previous_file_list);

            if (empty($uploaded_previous_file)){
                $mediaList = Media::where('mediable_id', $request->id)->get();

                if (count($mediaList) > 0){
                    foreach ($mediaList as $media){
                        $mediaName = substr($media->url, strpos($media->url, "media"));
                        unlink(public_path().'/'.$mediaName);
                        Media::find($media->id)->delete();
                    }
                }
            }

            if (isset($uploaded_previous_file) && count($uploaded_previous_file) > 0){
                $previousFileIds = array_column($uploaded_previous_file, 'id');
                $previousMediaList = Media::where('mediable_id', $request->id)->get();
                $previousMediaListIdArray = $previousMediaList->pluck('id')->toArray();

                $previousFileDiff = array_diff($previousMediaListIdArray,$previousFileIds);

                if ($previousFileDiff){
                    foreach($previousFileDiff as $aPreviousMedia)
                    {
                        $media = Media::where('id', $aPreviousMedia)->first();
                        $mediaName =  substr($media->url, strpos($media->url, "media") );
                        unlink(public_path().'/'.$mediaName );
                        Media::find($aPreviousMedia)->delete();

                    }
                }
            }

            if(isset($request->uploaded_file) && count($request->uploaded_file)>0) {

                $article = $this->getItemById($request->id);

                $thumbImageList = $request->uploaded_file;

                $fileLength = count($thumbImageList);

                if(count($thumbImageList) > 0) {
                    for ($i = 1; $i <= $fileLength; $i++) {

                        $thumbFile[] = Helper::fileUpload("article/files", $thumbImageList[count($thumbImageList) - $i]);

                    }
                }

                foreach ($thumbFile as $file):

                    $article->media()->create([

                        'url' => $file

                    ]);

                endforeach;
            }
            //notification
            if($article->is_notifiable == 1) {
                $articleNotification = $this->sendArticleNotification($input['id'] , 'update');
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
            'messages'    => config('status.status_code.200')
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

            //notification
            $article = $this->getItemById($id);
            // if($article->is_notifiable == 1) {
            //     $articleNotification = $this->sendArticleNotification($id , 'delete');
            // }

            $article->history()->create([
                'user_id' => auth()->user()->id,
                'post_id' => $id,
                'operation_type' => 'delete'
            ]);
            $this->articleRepository->delete($id);

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
            'messages'    => "Article Deleted Successfully"

        ]);

    }

    /**
     * @return JsonResponse
     */
    public function paginateData($request)
    {

        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'article_list' => $this->articleRepository->getWithPagination($request)

        ]);

    }

    public function sendArticleNotification($article_id, $type) {

        $article = $this->getItemById($article_id);
        $users = $this->articleRepository->getAllUsers();
        SendEmailJob::dispatch($article, $users , $type, 'article')
        ->delay(now()->addSeconds(3));
        //return $notifications = Helper::sendArticleNotification($article, $users, $type);
    }

    public function searchArticle($request,$searchString)
    {

        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'article_list' => $this->articleRepository->search($request,$searchString)

        ]);

    }


    public function categoryArticle($request,$id)
    {

        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'article_list' => $this->articleRepository->searchCategoryArticle($request,$id)

        ]);

    }


    public function saveFiles($request)
    {

        if(isset($request->file)) {

            return response()->json([

                'status_code' => 200,
                'messages'    => config('status.status_code.200'),
                'file_path'   => Helper::base64ContentImageUpload($request->attachment_save_path, $request->file)

            ]);

        }

    }


    public function deleteFiles($request)
    {

        if(isset($request->img_src)) {

            $mediaName = substr($request->img_src, strpos($request->img_src, "media"));

            unlink(public_path().'/'.$mediaName );

            return response()->json([
                'status_code' => 200,
                'messages'    => config('status.status_code.200'),
            ]);

        }

    }

    public function articleStatusChange($request)
    {
        $article = $this->getItemById($request->id);

        $article->history()->create([
            'user_id' => auth()->user()->id,
            'post_id' => $request->id,
            'operation_type' => 'status changed'
        ]);


        return response()->json([

            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'article_info' => $this->articleRepository->changeStatus($request)

        ]);

    }


//    public function articleCommentStatusChange($request){
//        return response()->json([
//
//            'status_code'  => 200,
//            'messages'     => config('status.status_code.200'),
//            'article_info' => $this->articleRepository->changeArticleCommentStatus($request)
//
//        ]);
//    }


}
