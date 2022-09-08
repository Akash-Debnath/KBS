<?php


namespace App\Repositories;

use App\Http\Traits\QueryTrait;
use App\Models\Page;

class PageRepository
{

    use QueryTrait;
    /**
     * @return mixed
     */
    public function all()
    {

        return Page::Query()->first();

    }


    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return Page::findOrFail($id);
    }


    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {

        $dataObj =  new Page;

        $dataObj->id  = $data['id'];
        $dataObj->logo = $data['logo_url'] ?? null;
        $dataObj->position = $data['position'] ?? 'left';
        $dataObj->title = $data['title'];
        $dataObj->banner = $data['banner_url'] ?? null;
        $dataObj->description = $data['description'] ?? null;

        return $dataObj->save();
    }


    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        return Page::find($id)->update($data);
    }



}
