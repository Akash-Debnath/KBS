<?php

namespace App\Repositories;

use App\Helpers\Helper;
use App\Http\Traits\QueryTrait;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements RepositoryInterface
{
    use QueryTrait;
    /**
     * @return mixed
     */
    public function all()
    {

        return User::with([
            'roles' => function($q) {$q->select('id', 'name');}, 'media'])
            ->orderBy('id', 'DESC')
            ->get();

    }


    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {

        return User::with([
            'media',
            'roles' => function($query){
                $query->select('id', 'name', 'slug');
            },
            'roles.groups' => function($query){
                $query->select('id', 'name', 'slug');
            },
            'roles.permissions' => function($query){
                $query->select('id', 'name', 'slug');
        }])->findOrFail($id);

    }


    public function getUser($id)
    {
        return User::findOrFail($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {

        $dataObj =  new User;
        $dataObj->id = $data['id'];
        $dataObj->username = $data['username'];
        $dataObj->first_name = $data['first_name'];
        $dataObj->last_name = $data['last_name'];
        $dataObj->slug = Helper::slugify($data['first_name'].$data['last_name']) ;
        $dataObj->email = $data['email'];
        $dataObj->password = $data['password'];
        $dataObj->status = $data['status'] ?? 1;

        return $dataObj->save();
    }


    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        return User::find($id)->update($data);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return User::find($id)->delete();
    }


    /**
     * @return LengthAwarePaginator
     */
    public function getWithPagination($request)
    {
        $query = User::with(['roles' => function($q) {$q->select('id', 'name');}, 'media']);
        $whereFilterList = ['email'];
        $likeFilterList  = ['username'];
        $query = self::filterUser($request, $query, $whereFilterList, $likeFilterList);
        return $query->orderBy('created_at','DESC')->paginate(20);
    }


    public static function filterUser($request, $query, array $whereFilterList, array $likeFilterList)
    {
        $query = self::likeQueryFilter($request, $query, $likeFilterList);
        $query = self::whereQueryFilter($request, $query, $whereFilterList);

        return $query;

    }

}
