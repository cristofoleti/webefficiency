<?php

namespace Webefficiency\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use View;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Controller constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        if(! \Auth::guest()){
            $companies = $request->user()->companies;
            View::share('global_companies', $companies);
        }
    }

    protected function responseJson($data)
    {
        return \Response::json($data);
    }
}
