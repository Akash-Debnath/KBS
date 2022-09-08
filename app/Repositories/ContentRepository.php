<?php


namespace App\Repositories;


use App\Models\Article;
use App\Models\Faq;
use App\Models\Content;

class ContentRepository
{
    public function create($request){

        $content = new Content();
        $content->id            = $request->id;
        $content->article_id    = $request->article_id;
        $content->en_body       = $request->en_body!= null ? $request->en_body : 'n/a';
        $content->bn_body       = $request->bn_body!= null ? $request->bn_body : 'n/a';
        $content->role_id       = $request->role_id;
        return $content->save();
    }

    public function get($id){
        return Content::where('id', $id)->first();
    }

    public function update($request){

        $content = Content::find($request->id);
        $content->article_id = $request->article_id;
        $content->en_body = $request->en_body!= null ? $request->en_body : 'n/a';
        $content->bn_body = $request->bn_body != null ? $request->bn_body : 'n/a';
        $content->role_id = $request->role_id;
        return $content->save();

    }

    public function delete($id){

        $content = Content::find($id);
        if ($content){
            return $content->delete();
        }
        else{
            return 'no content found';
        }
    }

    public function articleContentShow($id){

        $contents = Content::where('article_id', $id)->orderBy('created_at', 'desc')->get();
        if ($contents){
            return $contents;
        }else{
            return 'no content found';
        }
    }

    public function articleAvailabilty($id){

        $article = Article::find($id);
        if (empty($article)){
            $contents = Content::where('article_id', $id)->orderBy('created_at', 'desc')->get();
            if ($contents){
                foreach ($contents as $content){
                    $content->delete();
                }
            }
            else{
                return 'no content found';
            }

        }else{
            return 'article found';
        }
    }
    public function faqAvailabilty($id){

        $faq = Faq::find($id);
        if (empty($faq)){
            $contents = Content::where('article_id', $id)->orderBy('created_at', 'desc')->get();
            if ($contents){
                foreach ($contents as $content){
                    $content->delete();
                }
            }
            else{
                return 'no content found';
            }

        }else{
            return 'article found';
        }
    }
}