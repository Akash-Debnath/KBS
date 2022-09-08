<?php


namespace App\Repositories;


use App\Http\Traits\QueryTrait;
use App\Models\Article;
use function MongoDB\BSON\toJSON;

class searchReplaceRepository
{
    use QueryTrait;

    public function search($post_id,$search){

        $article =  Article::with('category','contents','user','media')
            ->where('id',$post_id)->first();

        if ($article){
            return $article;
        }
        else{
            return 'Not found!';
        }

    }


    public function replace($post_id,$search,$request){

        $article =  Article::with('category','contents','user','media')
                    ->where('id',$post_id)->first();


        if ($article){
            $article->en_title = str_replace($search,$request->replace_word, $article->en_title);
            $article->bn_title = str_replace($search,$request->replace_word, $article->bn_title);
            $article->en_short_summary = str_replace($search,$request->replace_word, $article->en_short_summary);
            $article->bn_short_summary = str_replace($search,$request->replace_word, $article->bn_short_summary);
            $article->tag = str_replace($search,$request->replace_word, $article->tag);
            foreach ($article->contents as $content){

                if(strpos($content->en_body, $search) !== false){
                    $content->en_body = str_replace($search,$request->replace_word, $content->en_body );
                    $content->save();
                }
                if (strpos($content->bn_body, $search) !== false){
                    $content->bn_body = str_replace($search,$request->replace_word, $content->bn_body );
                    $content->save();
                }

            }
            $article->save();

            return $article;
        }else{
            return 'Not found!';
        }


//            ->whereHas('contents', function ($q) use ($search,$post_id){
//                $q->where('en_body', 'like', '%'.$search.'%');
//                $q->orWhere('bn_body', 'like', '%'.$search.'%');
//            })
//            ->orWhere('en_title', 'like', "%{$search}%")
//            ->orWhere('bn_title', 'like', "%{$search}%")
//            ->orWhere('en_short_summary', 'like', "%{$search}%")
//            ->orWhere('bn_short_summary', 'like', "%{$search}%")
//            ->orWhere('tag', 'like', "%{$search}%")
//            ->orWhere('en_short_summary', 'like', "%{$search}%")
//            ->where('status', 'public')
//            ->orderBy('created_at', 'DESC')
//            ->first();
//        return $article;
    }

}