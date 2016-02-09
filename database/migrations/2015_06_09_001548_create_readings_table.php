<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('readings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->index();
            $table->date('date');
            $table->time('time');
            $table->double('timestamp')->default(0);
            $table->decimal('te_ret')->default(0);
            $table->decimal('vazao')->default(0);
            $table->decimal('consumo')->default(0);
            $table->decimal('tr_cag')->default(0);
            $table->decimal('kw_tr')->default(0);
            $table->decimal('dif_temp')->default(0);
            $table->decimal('te_alim')->default(0);
            $table->decimal('pot_inst')->default(0);
            $table->decimal('te_sai_torre')->default(0);
            $table->decimal('w_abl')->default(0);
            $table->decimal('w_ac')->default(0);
            $table->decimal('tbu_calculado')->default(0);
            $table->decimal('corrente_york')->default(0);
            $table->decimal('corrente_trane1')->default(0);
            $table->decimal('corrente_trane2')->default(0);
            $table->decimal('umidade_relativ')->default(0);
            $table->decimal('temp_externa')->default(0);
            $table->decimal('rend_torres')->default(0);
            $table->decimal('app_evap_trane1')->default(0);
            $table->decimal('app_evap_trane2')->default(0);
            $table->decimal('app_evap_york')->default(0);
            $table->decimal('app_cond_trane1')->default(0);
            $table->decimal('app_cond_trane2')->default(0);
            $table->decimal('app_cond_york')->default(0);
            $table->decimal('app_desc_trane1')->default(0);
            $table->decimal('app_desc_trane2')->default(0);
            $table->decimal('app_desc_york')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('readings');
    }
}
