<?php

namespace Webefficiency\Http\Controllers;

use Carbon\Carbon;
use Webefficiency\Company;
use Webefficiency\Reading;
use Webefficiency\Variable;
use Illuminate\Http\Request;
use Webefficiency\Http\Requests;

class ReportsController extends Controller
{

    public function index()
    {
        $variables = Variable::all();

        return view('reports.index', compact('variables'));
    }

    public function variables(Request $request)
    {
        $this->validate($request, [
            'variable_tag' => 'required',
            'start_date_submit' => 'required|date|before:end_date_submit',
            'end_date_submit' => 'required|date|after:start_date_submit',
        ]);

        $variable = Variable::where('tag', $request->get('variable_tag'))->first();
        $company = Company::findOrFail(session('default_company'));

        $readings = $company->readings()
            ->whereBetween('date', [$request->get('start_date_submit'), $request->get('end_date_submit')])
            ->get();

        $data = [];
        foreach ($readings as $reading) {
            $data[] = [
                'date' => $reading->date ? with(new Carbon($reading->date))->format('d/m/Y') : null,
                'time' => $reading->time,
                $variable->tag => number_format($reading->{$variable->tag}, 2, ',', '.'),
            ];
        }

        return view('reports.variables', compact('variable', 'company', 'data'));
    }
}
