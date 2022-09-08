<?php

namespace App\Repositories;


use App\Helpers\Helper;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Object_;

class MenuRepository implements RepositoryInterface
{
    public $subCat =[];

    /**
     * @return mixed
     */

    public function all()
    {
        // return Menu::with('childrenRecursive','media')
        //     ->where('parent_id', '=', 0)
        //     ->orderBy('id','DESC')
        //     ->get();

        return Menu::with('parentRecursive')
            ->orderBy('created_at','DESC')
            ->get();
    }

    
    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return Menu::where('id', $id)->first();

        //return Category::with(['permissions' => function($q) {$q->select('id', 'name');}])->find($id);

    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {

        $dataObj =  new Menu;

        $dataObj->id = $data['id'];
        $dataObj->name = $data['name'];
        $dataObj->order_number = $data['order_number'];
        $dataObj->slug = Helper::slugify($data['name']);
        $dataObj->parent_id = $data['parent_id'] ?? 0;
        // $dataObj->user_id = $data['user_id'] ?? 0;
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
        // dd($data);

        return Menu::where('id', $id )->update([
            'name'        => $data['name'],
            'status'      => $data['status'],
            'slug'        => $data['slug'],
            'order_number' => $data['order_number'],
            'parent_id'   => $data['parent_id'] ?? 0
        ]);

    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {

        self::deleteSubcategory($id);

    }


    /**
     * @param $category_id
     */
    public static function deleteSubcategory($category_id){

        $request = Menu::where('parent_id', '=', $category_id)->pluck('id');

        foreach ($request as $cat){

            self::deleteSubcategory($cat);

        }
        //echo 'hi';
        Menu::where('id', '=', $category_id)->delete();

    }

    public function getWithPagination($request)
    {   
        if ($request->filled('isAdmin') && $request->filled('isList') && $request->filled('isRole')){
        return Menu::with('parentRecursive')
                //->where('role_id','LIKE','%'.$request->isRole.'%')
                ->orderBy('created_at','DESC')
                ->paginate(20);
        } else {
            return Menu::with('parentRecursive')
            ->where('parent_id',0)
            ->orderBy('created_at','DESC')
            ->get();
        }


        // if ($request->filled('isAdmin') && $request->filled('isList') && $request->filled('isRole')){
        //     return Menu::with('parentRecursive','media')
        //         ->where('role_id','LIKE','%'.$request->isRole.'%')
        //         ->orderBy('id','DESC')
        //         ->paginate(20);
        // }
        // elseif ($request->filled('isAdmin') && $request->filled('isRole')){
        //     return Menu::with('parentRecursive','media')
        //         ->where('role_id','LIKE','%'.$request->isRole.'%')
        //         ->orderBy('id','DESC')
        //         ->get();
        // }
    }


    public function allMenu()
    {
        return Menu::with('children')->where('parent_id',0)->orderBy('order_number','ASC')->get();
    }

    public function menuPageData($slug)
    {
        $menu = Menu::with('page.file')->where('slug',$slug)->orderBy('order_number','ASC')->first();

        if($menu->parent_id) {
            $menu->side_menu =  Menu::where(['parent_id'=>$menu->parent_id])->orderBy('order_number','ASC')->get();
        } else {
            $menu->side_menu = '';
        }
        return $menu;
    }

}
