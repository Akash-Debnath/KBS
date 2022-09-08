<?php

namespace App\Repositories;


use App\Helpers\Helper;
use App\Models\Link;
use Illuminate\Support\Facades\DB;


class LinkRepository implements RepositoryInterface
{


  
    public $subCat =[];

    /**
     * @return mixed
     */

    public function all()
    {
        return Link::where('status',1)->get();
    }

    
    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return Link::where('id', $id)->first();

        //return Category::with(['permissions' => function($q) {$q->select('id', 'name');}])->find($id);

    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {

        $dataObj =  new Link;

        $dataObj->id = $data['id'];
        $dataObj->title = $data['title'];
        $dataObj->url = $data['url'];
        $dataObj->status = $data['status'];

        return $dataObj->save();
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        return Link::where('id', $id )->update([
            'title'     => $data['title'],
            'url'       => $data['url'],
            'status'    => $data['status'],
        ]);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        Link::where('id', '=', $id)->delete();
    }


    /**
     * @param $category_id
     */
    public static function deleteSubcategory($category_id){

        $request = Link::where('parent_id', '=', $category_id)->pluck('id');

        foreach ($request as $cat){

            self::deleteSubcategory($cat);

        }
        Link::where('id', '=', $category_id)->delete();

    }

    public function getWithPagination($request)
    {   
        if ($request->filled('isAdmin') && $request->filled('isList') && $request->filled('isRole')){
        return Link::orderBy('created_at','DESC')->paginate(20);
        } else {
            return Link::orderBy('created_at','DESC')->get();
        }

    }


}
