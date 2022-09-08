<?php


namespace App\Repositories;


use App\Models\Comment;

class CommentRepository
{
    public function create(array $data){

        $dataObj =  new Comment();
        $dataObj->id = $data['id'];
        $dataObj->post_id = $data['post_id'];
        $dataObj->user_id = $data['user_id'];
        $dataObj->comment_body = $data['comment_body'];
        $dataObj->status = $data['status'];
        return $dataObj->save();
    }

    public  function articleCommentsAll($id){
        $comments = Comment::with('article','faq','user')->where('post_id',$id)->orderBY('created_at','DESC')->take(5)->get();
//        dd($comments);
        return $comments;
    }

    public function articleCommentsVisitorAll($id){
//        return $comments = Comment::where('post_id',$id)->where('status',1)->orderBY('created_at','DESC')->get();
        return $comments = Comment::with('user','article','faq')->where('post_id',$id)->where('status',1)->orderBY('created_at','DESC')->take(5)->get();
    }

    public function commentStatus($request){
        $comment = Comment::find($request->id);
        $comment->status = $request->status;
        return $comment->save();
    }

    public function update($request){
        $comment = Comment::find($request->id);
        $comment->comment_body = $request->comment_body;
        $comment->status = 0;
        return $comment->save();
    }

    public function delete($id)
    {
        return Comment::find($id)->delete();
    }

    public function getWithPagination(){

        return Comment::with('user','article','faq')->orderBY('created_at','DESC')->paginate(20);

    }
}