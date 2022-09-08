<?php


namespace App\Repositories;


use App\Models\CrudHistory;

class CrudHistoryRepository
{
    public function getAllWithPagination()
    {

        return CrudHistory::with('user')->orderBy('created_at','DESC')->paginate(20);

    }

    public function getWithPagination($post_id)
    {

        return CrudHistory::with('user')->where('post_id',$post_id)->orderBy('created_at','DESC')->paginate(20);

    }

    public function delete($request){
        $histories = CrudHistory::where('post_id',$request->post_id)->get();

        if ($histories){
            foreach ($histories as $history){
                $history->delete();
            }
        }
    }
}