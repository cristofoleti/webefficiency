<?php

use Illuminate\Database\Seeder;
use Webefficiency\Variable;

class VariableTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Variable::create([
            'active' => 1,
            'tag' => 'te_ret',
            'description' => 'Temperatura de Retorno',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'vazao',
            'description' => 'Medição da Vazão',
            'unity' => 'm&sup3;/h'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'consumo',
            'description' => 'Consumo Elétrico',
            'unity' => 'Wh'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'tr_cag',
            'description' => 'Tr Produzido',
            'unity' => 'Tr'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'kw_tr',
            'description' => 'Índice de Performance da CAG',
            'unity' => 'Kw/Tr'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'dif_temp',
            'description' => 'Diferencial de temperatura da CAG',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'te_alim',
            'description' => 'Temperatura de Alimentação',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'pot_inst',
            'description' => 'Potencia Instantênea',
            'unity' => 'kW'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'te_sai_torre',
            'description' => 'Temperatura de Saida das Torres',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'w_abl',
            'description' => 'Potencia por Árera Bruta Locada',
            'unity' => 'W'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'w_ac',
            'description' => 'Potencia por Árera Construida',
            'unity' => 'W'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'tbu_calculado',
            'description' => 'Temperatura de Bulbo Umido',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'corrente_york',
            'description' => 'Corrente de carga do Chiller YORK',
            'unity' => '%'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'corrente_trane1',
            'description' => 'Corrente de carga do Chiller TRANE 1',
            'unity' => '%'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'corrente_trane2',
            'description' => 'Corrente de carga do Chiller TRANE 2',
            'unity' => '%'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'umidade_relativ',
            'description' => 'Umidade relativa do ar Externo',
            'unity' => '%'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'temp_externa',
            'description' => 'Temperatura do Ar externo',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'rend_torres',
            'description' => 'Rendimento das Torres',
            'unity' => '%'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'app_evap_trane1',
            'description' => 'Perdas do Evaporador TRANE 1',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'app_evap_trane2',
            'description' => 'Perdas do Evaporador TRANE 2',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'app_evap_york',
            'description' => 'Perdas do Evaporador YORK',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'app_cond_trane1',
            'description' => 'Perdas do Condensador TRANE 1',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'app_cond_trane2',
            'description' => 'Perdas do Condensador TRANE 2',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'app_cond_york',
            'description' => 'Perdas do Condensador YORK',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'app_desc_trane1',
            'description' => 'Perdas de Descarga TRANE 1',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'app_desc_trane2',
            'description' => 'Perdas de Descarga TRANE 2',
            'unity' => '&deg;C'
        ]);

        Variable::create([
            'active' => 1,
            'tag' => 'app_desc_york',
            'description' => 'Perdas de Descarga YORK',
            'unity' => '&deg;C'
        ]);
    }
}
