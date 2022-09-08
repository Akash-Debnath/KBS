<?php

namespace App\Http\Controllers;

use App\Services\FaqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Auth;

class FaqController extends Controller
{

    protected $faqService;

    public function __construct(FaqService $faqService)
    {

        $this->faqService = $faqService;

    }

    public function index(Request $request)
    {
        if(Auth::user()->can('faq-list')) {

            return $this->faqService->paginateData($request);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }

        // return $this->faqService->paginateData($request);

    }

    public function faqList(Request $request)
    {

        return $this->faqService->getFaqList($request);

    }


    /**
     * @return void
     */
    public function create()
    {

        /*return view('categorys.create',compact('permissions'));*/

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        if(Auth::user()->can('faq-create')) {

            return $this->faqService->createItem($request);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);
        }

    }

    /**
     * @param $id
     * @return void
     */
    public function edit($id)
    {
        //
    }


    public function show($id)
    {
//        if(Auth::user()->can('faq-list')) {
//
//            return $this->faqService->getById($id);
//
//        } else {
//
//            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);
//
//        }

        return $this->faqService->getById($id);

    }

    public function update(Request $request)
    {

        if(Auth::user()->can('faq-edit')) {

            return $this->faqService->updateItem($request);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }

    }



    public function destroy(Request $request)
    {
        if(Auth::user()->can('faq-delete')) {

            return  $this->faqService->deleteItem($request->id);

        } else {

            return response()->json(['status_code' => 424, 'messages'=>'User does not have the right permissions']);

        }

    }

    public function changeFAQStatus(Request $request){

        return $this->faqService->faqStatusChange($request);

    }

    public function faqSearch(string $searchString = "")
    {

//        return $searchString;
        return $this->faqService->searchFaq($searchString);

    }
}
