<?php


namespace App\Repositories;


use App\Models\EmailSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EmailSettingRepository implements RepositoryInterface
{
    /**
     * @return mixed
     */
    public function all()
    {
        return EmailSetting::Query()->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {

        return EmailSetting::with(['permissions' => function($q) {$q->select('id', 'name');}, 'users'])->findOrFail($id);

    }

    /**
     * @param array $data
     * @return Builder|Model
     */
    public function create(array $data)
    {
        return EmailSetting::create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        return EmailSetting::find($id)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return EmailSetting::find($id)->delete();
    }

}
