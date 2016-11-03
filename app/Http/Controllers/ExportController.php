<?php

namespace Webefficiency\Http\Controllers;

use Excel;
use Carbon\Carbon;
use Webefficiency\Company;
use Webefficiency\Reading;
use Illuminate\Http\Request;
use Webefficiency\Http\Requests;
use Illuminate\Database\Eloquent\Collection;
use Webefficiency\Variable;
//use Session;

class ExportController extends Controller
{
    public function excel()
    {
        return view('export.index');
    }

    public function generate(Request $request)
    {
        /** @var Carbon $start_date */
        $start_date = Carbon::createFromFormat('Y-m-d', request()->get('start'));

        /** @var Carbon $end_date */
        $end_date = Carbon::createFromFormat('Y-m-d', request()->get('end'));

        /** @var Company $company */
        $default_company = $request->session()->get('default_company');
        $company = Company::findOrFail($default_company);

        /** @var Variable $variable */
        $variable = Variable::findByTag($request->get('tag'));

        /** @var Collection $readings */
        $readings = $company->readings()->whereBetween('date', [
            $start_date->format('Y-m-d'), $end_date->format('Y-m-d'),
        ])->get();

        $data = [];
        /** @var Reading $reading */
        foreach ($readings as $reading) {
            $data[] = [
                'Data' => $reading->date,
                'Hora' => $reading->time,
                $variable->description => number_format($reading->{$variable->tag}, 2, ',', '.')
            ];
        }

        $data = Excel::create(md5(request()->user()->id . time()), function($excel) use($data) {
            $excel->sheet('Sheet', function($sheet) use($data) {
                $sheet->fromArray($data);
            });
        })->store('xls', public_path('exports/excel'), true);

        $file = $data['file'];
        $route = route('export.download', ['file' => $file]);

        return response()->json(['success' => true, 'message' => $route]);
    }

    public function download($file)
    {
        return response()->download(public_path('exports/excel/') . $file);
    }
}
