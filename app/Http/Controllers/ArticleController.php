<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ArticleService;
use Auth;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService)
    {

        //$this->middleware("auth");
        $this->articleService = $articleService;

    }

    public function index(Request $request)
    {
        
        if(Auth::user()->can('article-list')) {

            return $this->articleService->paginateData($request);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }

    }

    public function store(Request $request)
    {

        if(Auth::user()->can('article-create')) {

            return $this->articleService->createItem($request);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function show($id)
    {

        return $this->articleService->getById($id);

    }

    public function update(Request $request)
    {

        if(Auth::user()->can('article-edit')) {

            return $this->articleService->updateItem($request);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        
        if(Auth::user()->can('article-delete')) {

            return  $this->articleService->deleteItem($request->id);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }

    }

    /**
     * @param Request $request
     * @param Article $article
     * @return JsonResponse
     */
    public function articleSearch(Request $request,string $searchString = "")
    {

        return $this->articleService->searchArticle($request,$searchString);

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function articleCategory(Request $request,$id)
    {

        return $this->articleService->categoryArticle($request,$id);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function articleList(Request $request)
    {

        if ($request->filled('isRole')){
            return $this->articleService->getLatestList($request);
        }else{
            return $this->articleService->getLatestList($request);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveFiles(Request $request)
    {

        return $this->articleService->saveFiles($request);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteFiles(Request $request)
    {

        return $this->articleService->deleteFiles($request);

    }

    public function articleDetails($slug)
    {

        return $this->articleService->getArticleDetailsBySlug($slug);

    }

    public function changeArticleStatus(Request $request){

        return $this->articleService->articleStatusChange($request);

    }

//    public function changeArticleCommentStatus(Request $request){
//        return $this->articleService->articleCommentStatusChange($request);
//    }

    public function articleAll(Request $request){
        return $this->articleService->getAllArticleList($request);
    }

}
