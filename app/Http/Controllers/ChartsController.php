<?php

namespace Webefficiency\Http\Controllers;

use Webefficiency\Company;
use Webefficiency\Variable;
use Webefficiency\Http\Requests;

class ChartsController extends Controller
{
    public function variables()
    {
        $variables = Variable::all();

        return view('charts.index', compact('variables'));
    }

    public function variablesData($tag)
    {
        $company = Company::findOrFail(session('default_company'));

        $readings = $company->readings;

        $data = [];
        foreach ($readings as $reading) {
            $data[] = [
                intval($reading->timestamp),
                (double) $reading->{$tag}
            ];
        }

        return response()->json($data);
    }
}
