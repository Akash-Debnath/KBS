<?php


namespace App\Services;


use App\Repositories\TotalCountRepository;

class TotalCountService
{

    protected $totalCountRepository;

    public function __construct(TotalCountRepository $totalCountRepository)
    {

        $this->totalCountRepository = $totalCountRepository;

    }

    public function getTotalCount($request)
    {
        return response()->json([
            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'total_count'  => $this->totalCountRepository->allTotalCount($request)
        ]);
    }
}
