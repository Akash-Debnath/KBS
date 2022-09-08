<?php

namespace App\Repositories;

use App\Helpers\Helper;
use App\Http\Traits\QueryTrait;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Content;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class ArticleRepository implements RepositoryInterface
{
    use QueryTrait;
    /**
     * @return mixed
     */
    public function all()
    {
        return Article::with('user','category')->orderBy('id', 'DESC')->get();

    }

    public function allWithRole($request){
        if ($request->filled('isRole')&& $request->filled('isAdmin')){
            return Article::with('category')
                ->whereHas('category', function ($q) use ($request){
                    $q->where('role_id','LIKE','%'.$request->isRole.'%');
                })
                ->orderBy('id', 'DESC')->get();
        }
    }

    public function latestArticleList($request)
    {
        if ($request->isAdmin && $request->filled('isRole')){

            return Article::with('user','category','contents')
                //    ->whereHas('category', function ($q) use ($request){
                //     $q->where('role_id','LIKE','%'.$request->isRole.'%');
                //     })
                ->orderBy('created_at', 'DESC')
                ->take(5)
                ->get();

        }
        elseif ($request->isRole){
            $articles = Article::with('user','category','contents')
                ->where('status', 'public')
                ->orWhere('status', 'private')
                ->orderBy('created_at', 'DESC')
                ->take(6)
                ->get()
                ->map(function ($query) {
                    $query->setRelation('contents', $query->contents->take(1));
                    return $query;
                });
            return $articles->toArray();
        }
        else{

            $articles = Article::with('user','category','contents','media')
                ->where('status', 'public')
                ->orderBy('created_at', 'DESC')
                // ->take(6)
                ->get()
                ->map(function ($query) {
                    $query->setRelation('contents', $query->contents->take(1));
                    return $query;
                });
            return $articles->toArray();
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return Article::with('user','category','media','contents')->find($id);
        //return Content::select('role_id')->groupBy('role_id')->where('article_id',$id)->get();
    }


    public function getAllUsers()
    {
        return User::all();
    }

    public function getBySlug($slug)
    {
        return Article::with('user','category','media','contents')
            ->where('slug', $slug)
            ->first();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $randomString = Str::random();

        $dataObj =  new Article;

        $dataObj->id          = $data['id'];
        $dataObj->user_id     = $data['user_id'];
        $dataObj->category_id = $data['category_id'];
        $dataObj->en_title    = $data['en_title'];
        $dataObj->bn_title    = $data['bn_title']? $data['bn_title'] : 'n/a';
        $dataObj->tag         = $data['tag'];
        $dataObj->slug        = Helper::slugify($data['en_title']).$randomString;
        $dataObj->commentable_status      = $data['commentable_status'];
        $dataObj->is_notifiable           = $data['is_notifiable'];
        $dataObj->status      = $data['status'] ? $data['status'] :  'draft';
        $dataObj->publish_date = $data['publish_date'];
        $dataObj->en_short_summary = $data['en_short_summary'];
        $dataObj->bn_short_summary = $data['bn_short_summary']? $data['bn_short_summary'] : 'n/a';
        $dataObj->thumbnail_img = $data['thumbnail_url'];
        $dataObj->banner_img = $data['banner_url'];

        return $dataObj->save();

    }


    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        return Article::find($id)->update($data);
    }


    /**
     * @param $idgit
     * @return mixed
     */
    public function delete($id)
    {

        $contents = Content::where('article_id', $id)->orderBy('created_at', 'desc')->get();
        $comments = Comment::where('post_id', $id)->orderBy('created_at', 'desc')->get();
        if ($contents){
            foreach ($contents as $content){
                $content->delete();
            }
        }

        if ($comments){
            foreach ($comments as $comment){
                $comment->delete();
            }
        }

        return Article::find($id)->delete();
    }


    /**
     * @param $request
     * @return mixed
     */
    public function getWithPagination($request)
    {
        if ($request->filled('isRole'))
        {
//            $articles = Article::with('category','user','history')
//                ->whereHas('category', function ($q) use ($request){
//                    $q->where('role_id','LIKE','%'.$request->isRole.'%');
//                })->orderBy('created_at','DESC')->paginate(20);

            $articles = Article::with('category.groups.roles','user','history')
                ->orderBy('created_at','DESC')->paginate(20);

            return $articles;
        }
        else
        {
            $query = Article::with('user', 'category','history');
            $whereFilterList = ['category_id', 'status'];
            $likeFilterList = ['en_title', 'tag'];
            $query = self::filterArticle($request, $query, $whereFilterList, $likeFilterList);

            return $query->orderBy('id', 'DESC')->paginate(20);
        }
    }


    /**
     * @param $request
     * @param $query
     * @param array $whereFilterList
     * @param array $likeFilterList
     * @return mixed
     */
    public static function filterArticle($request, $query, array $whereFilterList, array $likeFilterList)
    {
        $query = self::likeQueryFilter($request, $query, $likeFilterList);
        $query = self::whereQueryFilter($request, $query, $whereFilterList);

        if($request->filled('publish_date')){
            $query->whereDate('publish_date', '=', $request->publish_date);
        }

        return $query;
    }


    public function search($request,string $query = "")
    {
        $articleStatus = [];

        if ($request->filled('isRole'))
        {
            $articleStatus = ['public','private'];
        }
        else{
            $articleStatus = ['public'];
        }


        $itemsPaginated =  Article::with('category','contents')
            ->where(function($q) use ($query){
                $q->where('en_title', "like", '%'.$query.'%')
                    ->orWhere('tag', "like", '%'.$query.'%')
                    ->orWhere('en_short_summary', "like", '%'.$query.'%')

                    ->orWhereHas('contents', function ($sq) use ($query){
                        $sq->where('en_body','LIKE','%'.$query.'%');
                    })
                    ->orWhereHas('category', function ($sq) use ($query){
                        $sq->where('name','LIKE','%'.$query.'%');
                    });
            })
            ->whereIn('status', $articleStatus)
            ->orderBy('created_at', 'DESC')->paginate(5);


        $itemsTransformed = $itemsPaginated
            ->getCollection()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'category_id' => $item->category_id,
                    'en_title' => $item->en_title,
                    'bn_title' => $item->bn_title,
                    'tag' => $item->tag,
                    'slug' => $item->slug,
                    'en_short_summary' => $item->en_short_summary,
                    'bn_short_summary' => $item->bn_short_summary,
                    'commentable_status' => $item->commentable_status,
                    'status' => $item->status,
                    'publish_date' => $item->publish_date,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'category' => $item->category,
                    'contents' => $item->contents->take(1),
                ];
            })->toArray();

//        return $itemsTransformed;

        return $itemsTransformedAndPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $itemsTransformed,
            $itemsPaginated->total(),
            $itemsPaginated->perPage(),
            $itemsPaginated->currentPage(), [
                'path' => \Request::url(),
                'query' => [
                    'page' => $itemsPaginated->currentPage()
                ]
            ]
        );

    }



    public function searchCategoryArticle($request,$slug = '')
    {
        if ($request->filled('isRole')){

            $itemsPaginated = Article::with('category','contents')
                ->whereHas('category', function ($q) use ($slug){
                    $q->where('slug', $slug);
                })
                ->whereNotIn('status', ['draft','hide'])
                ->orderBy('created_at', 'DESC')
                ->paginate(5);
        }

        else
        {
            $itemsPaginated = Article::with('category','contents')
                ->whereHas('category', function ($q) use ($slug){
                    $q->where('slug', $slug);
                })
                ->where('status', 'public')
                ->orderBy('created_at', 'DESC')
                ->paginate(5);
        }

        $itemsTransformed = $itemsPaginated
            ->getCollection()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'category_id' => $item->category_id,
                    'en_title' => $item->en_title,
                    'bn_title' => $item->bn_title,
                    'tag' => $item->tag,
                    'slug' => $item->slug,
                    'en_short_summary' => $item->en_short_summary,
                    'bn_short_summary' => $item->bn_short_summary,
                    'commentable_status' => $item->commentable_status,
                    'status' => $item->status,
                    'publish_date' => $item->publish_date,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'category' => $item->category,
                    'contents' => $item->contents->take(1),
                ];
            })->toArray();

        return $itemsTransformedAndPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $itemsTransformed,
            $itemsPaginated->total(),
            $itemsPaginated->perPage(),
            $itemsPaginated->currentPage(), [
                'path' => \Request::url(),
                'query' => [
                    'page' => $itemsPaginated->currentPage()
                ]
            ]
        );

        return $itemsPaginated;


    }


    public function changeStatus($request){

        return Article::where('id', $request->id)->update([

            'status' => $request->status

        ]);

    }

}
