<?php

namespace App\Repositories;


use App\Helpers\Helper;
use App\Models\InterestRate;
use Illuminate\Support\Facades\DB;


class InterestRateRepository implements RepositoryInterface
{


  
    public $subCat =[];

    /**
     * @return mixed
     */

    public function all()
    {
        return InterestRate::where('status', '=', 1)
            ->orderBy('created_at','DESC')
            ->get();
    }

    
    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return InterestRate::where('id', $id)->first();

        //return Category::with(['permissions' => function($q) {$q->select('id', 'name');}])->find($id);

    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {

        $dataObj =  new InterestRate;

        $dataObj->id = $data['id'];
        $dataObj->title = $data['title'];
        $dataObj->rate = $data['rate'];
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
        return InterestRate::where('id', $id )->update([
            'title'     => $data['title'],
            'rate'      => $data['rate'],
            'status'    => $data['status'],
        ]);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {

        // self::deleteSubcategory($id);
        InterestRate::where('id', '=', $id)->delete();

    }


    /**
     * @param $category_id
     */
    public static function deleteSubcategory($category_id){

        $request = InterestRate::where('parent_id', '=', $category_id)->pluck('id');

        foreach ($request as $cat){

            self::deleteSubcategory($cat);

        }
        InterestRate::where('id', '=', $category_id)->delete();

    }

    public function getWithPagination($request)
    {   
        if ($request->filled('isAdmin') && $request->filled('isList') && $request->filled('isRole')){
        return InterestRate::orderBy('created_at','DESC')->paginate(20);
        } else {
            return InterestRate::orderBy('created_at','DESC')->get();
        }

    }


}
