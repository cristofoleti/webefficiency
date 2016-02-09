<?php

namespace Webefficiency;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{

    protected $fillable = [
        'company_id',
        'date',
        'time',
        'timestamp',
        'te_ret',
        'vazao',
        'consumo',
        'tr_cag',
        'kw_tr',
        'dif_temp',
        'te_alim',
        'pot_inst',
        'te_sai_torre',
        'w_abl',
        'w_ac',
        'tbu_calculado',
        'corrente_york',
        'corrente_trane1',
        'corrente_trane2',
        'umidade_relativ',
        'temp_externa',
        'rend_torres',
        'app_evap_trane1',
        'app_evap_trane2',
        'app_evap_york',
        'app_cond_trane1',
        'app_cond_trane2',
        'app_cond_york',
        'app_desc_trane1',
        'app_desc_trane2',
        'app_desc_york',
    ];

    /**
     * Get reading company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
