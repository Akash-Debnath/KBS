<?php

namespace App\Http\Controllers;

use App\Models\EmailSetting;
use Illuminate\Http\Request;
use App\Services\EmailSettingService;

class EmailSettingController extends Controller
{
    private $emailSettingService;

    /**
     * PermissionController constructor.
     * @param EmailSettingService $emailSettingService
     */


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(EmailSettingService $emailSettingService)
    {

        $this->emailSettingService = $emailSettingService;

    }


    public function index()
    {
        //
        return $this->emailSettingService->getAll();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        return $this->emailSettingService->createItem($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmailSetting  $emailSetting
     * @return \Illuminate\Http\Response
     */
    public function show(EmailSetting $emailSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmailSetting  $emailSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailSetting $emailSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmailSetting  $emailSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        return $this->emailSettingService->updateItem($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmailSetting  $emailSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailSetting $emailSetting)
    {
        //
    }
}
