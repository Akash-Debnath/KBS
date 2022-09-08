<?php


namespace App\Repositories;


use App\Models\Notification;

class NotificatrionRepository
{
    
    public function getWithPagination() {

        return Notification::with('user')->orderBY('created_at','DESC')->paginate(20);

    }

    public function delete($id) {
        return Notification::find($id)->delete();
    }

}