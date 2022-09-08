<?php


namespace App\Repositories;


use App\Models\Group;

class GroupRepository
{
    public function listing($request)
    {
        if ($request->filled('without_pagination')) {

            return Group::orderBy('created_at','DESC')->get();

        }
        if ($request->filled('query')){
            $query = new Group();
            $likeFilterList = ['name'];
            $whereFilterList = ['name'];
            $query = self::filterTask($request,$query,$whereFilterList,$likeFilterList);
            return $query->with('roles')->orderBy('created_at','DESC')->paginate(20);
        }else{
            return Group::with('roles')->orderBy('created_at','DESC')->paginate(20);
        }

    }

    public function create(array  $data)
    {

        $dataObj              = new Group;
        $dataObj->name        = $data['name'];
        $dataObj->description = isset($data['description']) ? $data['description'] : null;
        $dataObj->slug        = $data['slug'];
        $dataObj->save();
        return $dataObj;

    }

    public function show($id)
    {
        if (!empty($id)){
            return Group::with('roles')->findorfail($id);
        }else{
            return Group::with('roles')->orderBy('created_at','DESC')->take(1)->get();
        }

    }

    public function update(array $data, $id)
    {
        $dataObj            = Group::findorfail($id);
        $dataObj->name      = $data['name'];
        $dataObj->slug      = $data['slug'];
        $dataObj->description = isset($data['description']) ? $data['description'] : null;
        return $dataObj->save();
    }

    public function delete($id)
    {

        return Group::find($id)->delete();
    }

    public static function filterTask($request, $query, array $whereFilterList, array $likeFilterList)
    {
        $query = self::likeQueryFilter($request, $query, $likeFilterList);
        $query = self::whereQueryFilter($request, $query, $whereFilterList);

        return $query;

    }
}