<?php

namespace App\Http\Controllers;

use App\Services\TotalCountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TotalCountController extends Controller
{
    protected $totalCountService;

    public function __construct(TotalCountService $totalCountService)
    {

        $this->totalCountService = $totalCountService;

    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return JsonResponse
     */
    public function totalCount(Request $request)
    {

     return $this->totalCountService->getTotalCount($request);
       // dd($hello);
    }

}
