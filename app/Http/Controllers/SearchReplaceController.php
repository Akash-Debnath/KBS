<?php

namespace App\Http\Controllers;

use App\Services\SearchReplaceService;
use Illuminate\Http\Request;
use Auth;

class SearchReplaceController extends Controller
{
    protected $searchReplaceService;

    public function __construct(SearchReplaceService $searchReplaceService)
    {

        //$this->middleware("auth");
        $this->searchReplaceService = $searchReplaceService;

    }

    public function searchText($post_id,$search){

        if(Auth::user()->can('article-edit')) {

            return $this->searchReplaceService->getSearchResult($post_id,$search);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }

    }

    public function replaceText($post_id,$search,Request $request){

        if(Auth::user()->can('article-edit')) {

            return $this->searchReplaceService->getReplaceResult($post_id,$search,$request);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }
    }
}
