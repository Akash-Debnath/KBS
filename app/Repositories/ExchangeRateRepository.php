<?php

namespace App\Repositories;


use App\Helpers\Helper;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\DB;


class ExchangeRateRepository implements RepositoryInterface
{

    /**
     * @return mixed
     */

    public function all()
    {
        return ExchangeRate::where('status', '=', 1)
            ->orderBy('created_at','DESC')
            ->get();
    }

    
    /**
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return ExchangeRate::where('id', $id)->first();
    }



    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {

        $dataObj =  new ExchangeRate;

        $dataObj->id = $data['id'];
        $dataObj->currency_name = $data['currency_name'];
        $dataObj->currency_code = strtoupper($data['currency_code']);
        $dataObj->buying_rate = $data['buying_rate'];
        $dataObj->selling_rate = $data['selling_rate'];
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
        return ExchangeRate::where('id', $id )->update([
            'id' => $data['id'],
            'currency_name' => $data['currency_name'],
            'currency_code' => strtoupper($data['currency_code']),
            'buying_rate' => $data['buying_rate'],
            'selling_rate' => $data['selling_rate'],
            'status' => $data['status'],
        ]);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        ExchangeRate::where('id', '=', $id)->delete();
    }



    public function getWithPagination($request)
    {   
        if ($request->filled('isAdmin') && $request->filled('isList') && $request->filled('isRole')){
        return ExchangeRate::orderBy('created_at','DESC')->paginate(20);
        } else {
            return ExchangeRate::orderBy('created_at','DESC')->get();
        }

    }


}
