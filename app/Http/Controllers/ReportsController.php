<?php

namespace Webefficiency\Http\Controllers;

use Carbon\Carbon;
use Webefficiency\Company;
use Webefficiency\Reading;
use Webefficiency\Variable;
use Illuminate\Http\Request;
use Webefficiency\Http\Requests;
use DB;
use PDF;

class ReportsController extends Controller
{

    public function index()
    {
        return view('reports.index');
    }

    public function variablesIndex(){
        $variables = Variable::all();

        return view('reports.variables', compact('variables'));
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

        return view('reports.variables_modal', compact('variable', 'company', 'data'));
    }


    public function performanceIndex()
    {
        return view('reports.performance');
    }

    public function performance(Request $request){

        $company = Company::findOrFail(session('default_company'));
        $anos = self::getAno();

        //Informações Gerais - médias, max e min por ano
        $dados_ano_atual = self::readingsPorAno($anos['ano_atual_inicio'], $anos['ano_atual_fim'], $company);
        $dados_ano_anterior = self::readingsPorAno($anos['ano_anterior_inicio'], $anos['ano_anterior_fim'], $company);
        

        //Organizando os dados
        $data = ['ano_atual' => [
                    'inicio' => date('d/m/Y', strtotime($anos['ano_atual_inicio'])), 
                    'fim' => date('d/m/Y', strtotime($anos['ano_atual_fim'])), 
                    'data' => $dados_ano_atual
                ],
                'ano_anterior' => [
                    'inicio' => date('d/m/Y', strtotime($anos['ano_anterior_inicio'])), 
                    'fim' => date('d/m/Y', strtotime($anos['ano_anterior_fim'])), 
                    'data' => $dados_ano_anterior
                ]
            ];


        return view('reports.performance_modal', compact('variable', 'company', 'data'));
    }

    /*
     * Informações gerais para relatório de Performance, de acordo com ano e readings informadas
    */
    private function readingsPorAno($ano_inicio, $ano_fim, $company){
        $readings_anual = $company->readings()
            ->select(DB::raw('max(temp_externa) as max_temp_externa, min(temp_externa) as min_temp_externa, avg(temp_externa) as avg_temp_externa, 
                                max(umidade_relativ) as max_umidade_relativ, min(umidade_relativ) as min_umidade_relativ, avg(umidade_relativ) as avg_umidade_relativ, 
                                max(consumo) as max_consumo, min(consumo) as min_consumo, avg(consumo) as avg_consumo, 
                                max(kw_tr) as max_kw_tr, min(kw_tr) as min_kw_tr, avg(kw_tr) as avg_kw_tr'))
            ->whereBetween('date', [$ano_inicio, $ano_fim])->first();

        $dados = [
                "temperatura" => [
                    "max" => $readings_anual->max_temp_externa, 
                    "min" => $readings_anual->min_temp_externa,
                    "media" => round($readings_anual->avg_temp_externa,2)
                ],
                "umidade" => [
                    "max" => $readings_anual->max_umidade_relativ, 
                    "min" => $readings_anual->min_umidade_relativ,
                    "media" => round($readings_anual->avg_umidade_relativ,2)
                ],
                "consumo" => [
                    "max" => $readings_anual->max_consumo, 
                    "min" => $readings_anual->min_consumo,
                    "media" => round($readings_anual->avg_consumo,2)
                ],
                "performance" => [
                    "max" => $readings_anual->max_kw_tr, 
                    "min" => $readings_anual->min_kw_tr,
                    "media" => round($readings_anual->avg_kw_tr,2)
                ]
            ];

        return $dados;
    }

    /*
     * Informações gerais para gráfico do relatório de Performance, de acordo com períodos mensais e readings informadas
    */
    private function readingsPorMes($ano_inicio, $ano_fim, $company){

        $dados = [];
        $periodos = self::periodosMensais($ano_inicio, $ano_fim);

        foreach ($periodos as $periodo) {
            $readings_mensal = $company->readings()
                ->select(DB::raw('avg(temp_externa) as avg_temp_externa, 
                                    avg(consumo) as avg_consumo, 
                                    avg(kw_tr) as avg_kw_tr'))
                ->whereBetween('date', $periodo)->first();

            $dados["consumo"][] = round($readings_mensal->avg_consumo,2);
            $dados["temperatura"][] = round($readings_mensal->avg_temp_externa,2);
            $dados["performance"][] = round($readings_mensal->avg_kw_tr,2);
        }

        return [$dados, $periodos];

    }

    /*
     * Cria intervalos em meses de acordo com o ano informado
    */
    private function periodosMensais($ano_inicio, $ano_fim){

        $dt_ano_inicio = new \DateTime($ano_inicio);
        $dt_ano_fim = new \DateTime($ano_fim);
        $intervalo = \DateInterval::createFromDateString('1 month');
        $periodos = new \DatePeriod($dt_ano_inicio, $intervalo, $dt_ano_fim);
        $periodosMensais = [];
        $meses = [];

        foreach ($periodos as $mes) {
            $meses[] = $mes->format("Y-m-d");
        }

        $qtd = count($meses);

        foreach ($meses as $k => $mes) {
            
            $periodo_inicio = $mes;
            $periodo_fim = ($k == $qtd-1 ) ? $ano_fim : date('Y-m-d', strtotime('-1 day', strtotime($meses[$k+1])) );

            $periodosMensais[] = [$periodo_inicio, $periodo_fim];
        }

        return $periodosMensais;
    }

    /*
     * Cria os anos iniciais e finais para relatório
    */
    private function getAno(){

        $ano_atual_inicio = date('Y-m-d', strtotime("-1 year"));
        $ano_atual_fim = date('Y-m-d');

        $ano_anterior_inicio = date('Y-m-d', strtotime("-2 year"));
        $ano_anterior_fim = date('Y-m-d', strtotime("-1 day", strtotime($ano_atual_inicio)) );

        $anos = [
                'ano_atual_inicio' => $ano_atual_inicio, 
                'ano_atual_fim' => $ano_atual_fim,
                'ano_anterior_inicio' => $ano_anterior_inicio, 
                'ano_anterior_fim' => $ano_anterior_fim
                ];

        return $anos;
    }

    /*
     * Lista o nome dos meses de acordo com os períodos
    */
    private function getMeses($periodos){

        $meses = [];

        foreach ($periodos as $periodo) {
            setlocale(LC_ALL, NULL);
            setlocale(LC_ALL, 'pt_BR');  
            $datetime = strtotime($periodo[0]);
            $mes = utf8_encode(ucfirst(gmstrftime('%b', $datetime)));
            $meses[] = $mes; // Jan/15
        }
        return $meses;
    }

    /*
     * Gera dados para gráfico com médias mensais do ano atual e ano anterior
    */
    public function performanceGraph(Request $request){

        $company = Company::findOrFail($request->session()->get('default_company'));
        $anos = self::getAno();

        $grafico_ano_atual = self::readingsPorMes($anos['ano_atual_inicio'], $anos['ano_atual_fim'], $company);
        $grafico_ano_anterior = self::readingsPorMes($anos['ano_anterior_inicio'], $anos['ano_anterior_fim'], $company);

        $meses = self::getMeses($grafico_ano_atual[1]);

        $grafico = [
            "periodos" => $meses,
            "consumo" => [
                "ano_atual" => $grafico_ano_atual[0]["consumo"], 
                "ano_anterior" => $grafico_ano_anterior[0]["consumo"]
            ],
            "temperatura" => [
                "ano_atual" => $grafico_ano_atual[0]["temperatura"], 
                "ano_anterior" => $grafico_ano_anterior[0]["temperatura"]
            ],
            "performance" => [
                "ano_atual" => $grafico_ano_atual[0]["performance"], 
                "ano_anterior" => $grafico_ano_anterior[0]["performance"]
            ]
        ];

        return response()->json($grafico);
    }



    public function reportChart($report){

        $company = Company::findOrFail(session('default_company'));

        switch($report){
            case '1': 
                $title = "Kw/h x Temperatura Ambiente";
                $tooltip = "Comparativo do consumo pela temperatura ambiente. Utilize os botões ao lado para ajustar o período.";
                break;
            case '2': 
                $title = "CAG x Temperatura Ambiente";
                $tooltip = "Comparativo da performance pela temperatura ambiente. Utilize os botões ao lado para ajustar o período.";
                break;
            case '3': 
                $title = "Consumo total CAG";
                $tooltip = "Visão geral da performance. Utilize os botões ao lado para ajustar o período.";
                break;
            default:
                $title = "";
                $tooltip = "";
                break;
        }

        return view('reports.report_chart', compact('company','title','report'));
    }

    public function reportChartGraph(Request $request, $report){

        ini_set('memory_limit', '-1');

        $company = Company::findOrFail($request->session()->get('default_company'));

        if($company){

            switch($report){
                case '1': 
                    $data = self::reportData_01($company);
                    break;
                case '2': 
                    $data = self::reportData_02($company);
                    break;
                case '3': 
                    $data = self::reportData_03($company);
                    break;
                default:
                    $data = null;
                    break;
            }

            return response()->json($data);
        }else{
            return response()->json(null);
        }

    }

    private function reportData_01($company){

        $anos = self::getAno();

        $readings = $company->readings()
            ->select('timestamp','temp_externa','consumo')
            ->whereBetween('date', [$anos["ano_atual_inicio"], $anos["ano_atual_fim"]])
            ->orderBy('timestamp')->get();

        $data = [];
        $data[0]["name"] = "Temperatura ambiente";
        $data[0]["suffix"] = " °C";
        $data[1]["name"] = "Consumo";
        $data[1]["suffix"] = " kWh";

        foreach ($readings as $reading) {
            $data[0]["data"][] = [
                $reading->timestamp,
                (double) $reading->temp_externa
            ];
            $data[1]["data"][] = [
                $reading->timestamp,
                (double) $reading->consumo
            ];
        }

        return $data;
    }


    private function reportData_02($company){

        $anos = self::getAno();

        $readings = $company->readings()
            ->select('timestamp','temp_externa','kw_tr')
            ->whereBetween('date', [$anos["ano_atual_inicio"], $anos["ano_atual_fim"]])
            ->orderBy('timestamp')->get();

        $data = [];
        $data[0]["name"] = "Temperatura ambiente";
        $data[0]["suffix"] = " °C";
        $data[1]["name"] = "CAG";
        $data[1]["suffix"] = " Kw/Tr";

        foreach ($readings as $reading) {
            $data[0]["data"][] = [
                $reading->timestamp,
                (double) $reading->temp_externa
            ];
            $data[1]["data"][] = [
                $reading->timestamp,
                (double) $reading->kw_tr
            ];
        }

        return $data;
    }


    private function reportData_03($company){

        $anos = self::getAno();

        $readings = $company->readings()
            ->select('timestamp','kw_tr')
            ->whereBetween('date', [$anos["ano_atual_inicio"], $anos["ano_atual_fim"]])
            ->orderBy('timestamp')->get();

        $data = [];
        $data[0]["name"] = "CAG";
        $data[0]["suffix"] = " Kw/Tr";

        foreach ($readings as $reading) {
            $data[0]["data"][] = [
                $reading->timestamp,
                (double) $reading->kw_tr
            ];
        }

        return $data;
    }
   

}
