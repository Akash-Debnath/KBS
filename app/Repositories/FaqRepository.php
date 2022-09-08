<?php

namespace App\Repositories;

use App\Helpers\Helper;
use App\Http\Traits\QueryTrait;
use App\Models\Faq;
use App\Models\Content;
use App\Models\User;

class FaqRepository implements RepositoryInterface
{
    use QueryTrait;
    /**
     * @return mixed
     */
    public function all()
    {

        return Faq::with( 'user', 'category')
        ->orderBy('id','DESC')
        ->get();

    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return Faq::with( 'user', 'category','contents')->find($id);
    }

    public function getAllUsers()
    {
        return User::all();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {

        $dataObj =  new Faq;
        $dataObj->id = $data['id'];
        $dataObj->article_id = $data['article_id'];
        $dataObj->user_id = $data['user_id'];
        $dataObj->category_id = $data['category_id'];
        $dataObj->en_title = $data['en_title'];
        $dataObj->bn_title = $data['bn_title']? $data['bn_title'] : 'n/a';
        $dataObj->tag = $data['tag'];
        $dataObj->slug = Helper::slugify($data['en_title']);
        $dataObj->commentable_status = $data['commentable_status'];
        $dataObj->is_notifiable = $data['is_notifiable'];
        $dataObj->status = $data['status'];
        $dataObj->publish_date = $data['publish_date'];

        return $dataObj->save();

    }


    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {

//        return $data;

        return Faq::find($id)->update($data);

    }


    /**
     * @param $id
     */
    public function delete($id)
    {

        return Faq::find($id)->delete();
        $contents = Content::where('article_id', $id)->orderBy('created_at', 'desc')->get();
        if ($contents){
            foreach ($contents as $content){
                $content->delete();
            }
            Faq::find($id)->delete();
            return 'content article delete';
        }else{
            Faq::find($id)->delete();
            return 'no content found';
        }

    }

    /**
     * @param $request
     * @return mixed
     */
    public function getWithPagination($request)
    {
        if ($request->filled('isRole'))
        {
            $faqs = Faq::with('category','user','history')
                ->whereHas('category', function ($q) use ($request){
                    $q->where('role_id','LIKE','%'.$request->isRole.'%');
                })
                ->orderBy('created_at','DESC')->paginate(20);
            return $faqs;
        }else{

            $query = Faq::with( 'user', 'category','history');
            $whereFilterList = ['category_id', 'status'];
            $likeFilterList  = ['en_title', 'tag'];
            $query = self::filterFaq($request, $query, $whereFilterList, $likeFilterList);

            return $query->orderBy('id','DESC')->paginate(20);

        }
    }

    public function getFaqListForFrontend($request)
    {

        $query = Faq::with( 'user', 'category','contents');
        $whereFilterList = ['category_id', 'status'];
        $likeFilterList  = ['en_title', 'tag'];
        $query = self::filterFaq($request, $query, $whereFilterList, $likeFilterList);

        if ($request->isLatest){
            return $query->where('status', 'public')
                ->orderBy('created_at', 'DESC')
               // ->take(5)
                ->get();

        }else{

            return $query->where('status', 'public')
                ->orderBy('id','DESC')
                ->get();

        }

    }

    /**
     * @param $request
     * @param $query
     * @param array $whereFilterList
     * @param array $likeFilterList
     * @return mixed
     */
    public static function filterFaq($request, $query, array $whereFilterList, array $likeFilterList)
    {
        $query = self::likeQueryFilter($request, $query, $likeFilterList);
        $query = self::whereQueryFilter($request, $query, $whereFilterList);

        if($request->filled('publish_date')) {

            $query->whereDate('publish_date', '=', $request->publish_date);
        }

        return $query;

    }

    public function search(string $query = "")
    {
        return Faq::with('category','contents')
            ->whereHas('contents', function ($q) use ($query){
                $q->where('en_body', 'like', '%'.$query.'%');
            })
            ->orWhere('en_title', 'like', "%{$query}%")
            ->orWhere('tag', 'like', "%{$query}%")
            ->where('status', 'public')
            ->orderBy('id', 'DESC')->paginate(5);
    }

    public function changeStatus($request){

        return Faq::where('id', $request->id)->update([

            'status' => $request->status

        ]);

    }

}
