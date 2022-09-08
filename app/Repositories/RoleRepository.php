<?php


namespace App\Repositories;


use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RoleRepository implements RepositoryInterface
{
    /**
     * @return mixed
     */
    public function all()
    {
        return Role::orderBy('id', 'DESC')->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {

        return Role::with(['permissions' => function($q) {$q->select('id', 'name');}, 'users'])->findOrFail($id);

    }

    /**
     * @param array $data
     * @return Builder|Model
     */
    public function create(array $data)
    {
        return Role::create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        return Role::find($id)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return Role::find($id)->delete();
    }


    public function getWithPagination($request)
    {
        if ($request->filled('without_pagination')) {

            return Role::with(['permissions', 'users'])
                ->orderBy('id', 'DESC')
                ->get();

        }else{
            return Role::with(['permissions', 'users'])
                ->orderBy('id', 'DESC')
                ->paginate(20);
        }

    }
}
