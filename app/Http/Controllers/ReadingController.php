<?php

namespace Webefficiency\Http\Controllers;

use Illuminate\Http\Request;

use Webefficiency\Company;
use Webefficiency\Http\Requests;
use Webefficiency\Http\Controllers\Controller;

class ReadingController extends Controller
{

    public function dashboard($company_id)
    {
        $company = Company::findOrFail($company_id);

        $readings = $company->readings;

        $data = [];
        foreach ($readings as $reading) {
            $data[] = [
                intval($reading->timestamp),
                (double) $reading->kw_tr
            ];
        }

        return response()->json($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $company_id
     * @param $var
     * @param Request $request
     *
     * @return Response
     */
    public function index($company_id, $var, Request $request)
    {
        $readings = $this->readingRepository->skipPresenter()->getReadingsByDateRange($company_id, $var, $request);
        $data = [];
        foreach ($readings as $reading) {
            $data[] = [intval($reading->timestamp), (double) $reading->{$var}];
        }

        return \Response::json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
