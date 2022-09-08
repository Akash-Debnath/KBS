<?php


namespace App\Repositories;

use App\Http\Traits\QueryTrait;
use App\Models\DynamicPage;
use App\Models\DynamicPageFile;

class DynamicPageRepository
{

    use QueryTrait;
    /**
     * @return mixed
     */
    public function all()
    {

        return DynamicPage::with('allMenu')
        ->orderBy('created_at','DESC')
        ->paginate(20);
    }



    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return DynamicPage::where('id', $id)->with('file')->first();
    }



    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {

        $dataObj =  new DynamicPage;

        $dataObj->id  = $data['id'];
        $dataObj->title = $data['title'];
        $dataObj->menu_id = $data['menu_id'];
        $dataObj->short_summary = $data['short_summary'];
        $dataObj->en_body = $data['en_body'];
        $dataObj->status = $data['status'];
        $dataObj->banner_img = $data['banner_img'];
        return $dataObj->save();
    }


    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        DynamicPage::where('id', '=', $id)->delete();
    }


    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        return DynamicPage::find($id)->update($data);
    }


    public function saveFiles($file, $id)
    {
        $dataObj =  new DynamicPageFile;
        $dataObj->dynamic_page_id  = $id;
        $dataObj->file_name = $file;

        return $dataObj->save();
    }


    public function getWithPagination($request)
    {
        $query = DynamicPage::with(['menu']);
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
