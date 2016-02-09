<?php

namespace Webefficiency\Console\Commands;

use SplFileObject;
use League\Csv\Reader;
use Webefficiency\Reading;
use Webefficiency\Company;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class DataImporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webef:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sanitize and import data into the main database.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $companies = Company::where('fieldlogger_url', '<>', '')->get();

        foreach ($companies as $company) {
            $this->sanitize($company)->import($company);
        }

        $this->info("Readings processed.");
    }

    /**
     * Insert CSV data into database
     *
     * @param \Webefficiency\Entities\Company $company
     *
     * @return $this
     */
    private function import($company)
    {
        if (! \File::exists(storage_path('fieldlogger/' . $company->fieldlogger_id . '/MemFlash/MemFlash.csv'))) {
            return;
        }

        /** @var \Webefficiency\Entities\Reading $latestReading */
        $latestReading = $company->getLatestReading();

        /** @var \League\Csv\Reader $reader */
        $reader = Reader::createFromPath(storage_path('fieldlogger/' . $company->fieldlogger_id . '/MemFlash/MemFlash.csv'))
            ->setDelimiter(';')
            ->setEnclosure('"')
            ->setFlags(SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY)
            ->addFilter(function ($row) use ($latestReading) {
                if (! strtotime($row[0])) {
                    return false;
                }

                if (null == $latestReading) {
                    return true;
                }

                return (strtotime("{$row[0]} {$row[1]}") > strtotime("{$latestReading->date} {$latestReading->time}"));
            });

        foreach ($reader->fetchAll() as $row) {
            $data = [
                'company_id' => $company->id,
                'date' => date('Y-m-d', strtotime($row[0])),
                'time' => substr($row[1], 0, 5) . ":00",
                'timestamp' => strtotime("$row[0] " . substr($row[1], 0, 5) . ":00") * 1000,
                'te_ret' => (! isset($row[2]) || floatval($row[2]) < 0 || floatval($row[2]) > 25) ? 0 : floatval($row[2]),
                'vazao' => (! isset($row[3]) || floatval($row[3]) < 0 || floatval($row[3]) > 2000) ? 0 : floatval($row[3]),
                'consumo' => (! isset($row[4])) ? 0 : floatval($row[4]),
                'tr_cag' => (! isset($row[5]) || floatval($row[5]) < 0 || floatval($row[5]) > 3000) ? 0 : floatval($row[5]),
                'kw_tr' => (! isset($row[6]) || floatval($row[6]) < 0 || floatval($row[6]) > 2) ? 0 : floatval($row[6]),
                'dif_temp' => (! isset($row[7]) || floatval($row[7]) < 0 || floatval($row[7]) > 10) ? 0 : floatval($row[7]),
                'te_alim' => (! isset($row[8]) || floatval($row[8]) < 0 || floatval($row[8]) > 25) ? 0 : floatval($row[8]),
                'pot_inst' => (! isset($row[9]) || floatval($row[9]) < 0 || floatval($row[9]) > 2000) ? 0 : floatval($row[9]),
                'te_sai_torre' => (! isset($row[10]) || floatval($row[10]) < 0 || floatval($row[10]) > 2000) ? 0 : floatval($row[10]),
                'w_abl' => (! isset($row[11]) || floatval($row[11]) < 0 || floatval($row[11]) > 25) ? 0 : floatval($row[11]),
                'w_ac' => (! isset($row[12]) || floatval($row[12]) < 0 || floatval($row[12]) > 25) ? 0 : floatval($row[12]),
                'tbu_calculado' => (! isset($row[13]) || floatval($row[13]) < 0 || floatval($row[13]) > 30) ? 0 : floatval($row[13]),
                'corrente_york' => (! isset($row[14]) || floatval($row[14]) < 0 || floatval($row[14]) > 1000) ? 0 : floatval($row[14]),
                'corrente_trane1' => (! isset($row[15]) || floatval($row[15]) < 0 || floatval($row[15]) > 1000) ? 0 : floatval($row[15]),
                'corrente_trane2' => (! isset($row[16]) || floatval($row[16]) < 0 || floatval($row[16]) > 1000) ? 0 : floatval($row[16]),
                'umidade_relativ' => (! isset($row[17]) || floatval($row[17]) < 0 || floatval($row[17]) > 100) ? 0 : floatval($row[17]),
                'temp_externa' => (! isset($row[18]) || floatval($row[18]) < 0 || floatval($row[18]) > 30) ? 0 : floatval($row[18]),
                'rend_torres' => (! isset($row[19]) || floatval($row[19]) < 0 || floatval($row[19]) > 100) ? 0 : floatval($row[19]),
                'app_evap_trane1' => (! isset($row[20]) || floatval($row[20]) < 0 || floatval($row[20]) > 5) ? 0 : floatval($row[20]),
                'app_evap_trane2' => (! isset($row[21]) || floatval($row[21]) < 0 || floatval($row[21]) > 5) ? 0 : floatval($row[21]),
                'app_evap_york' => (! isset($row[22]) || floatval($row[22]) < 0 || floatval($row[22]) > 5) ? 0 : floatval($row[22]),
                'app_cond_trane1' => (! isset($row[23]) || floatval($row[23]) < 0 || floatval($row[23]) > 5) ? 0 : floatval($row[23]),
                'app_cond_trane2' => (! isset($row[24]) || floatval($row[24]) < 0 || floatval($row[24]) > 5) ? 0 : floatval($row[24]),
                'app_cond_york' => (! isset($row[25]) || floatval($row[25]) < 0 || floatval($row[25]) > 5) ? 0 : floatval($row[25]),
                'app_desc_trane1' => (! isset($row[26]) || floatval($row[26]) < 0 || floatval($row[26]) > 5) ? 0 : floatval($row[26]),
                'app_desc_trane2' => (! isset($row[27]) || floatval($row[27]) < 0 || floatval($row[27]) > 5) ? 0 : floatval($row[27]),
                'app_desc_york' => (! isset($row[28]) || floatval($row[28]) < 0 || floatval($row[28]) > 5) ? 0 : floatval($row[28])
            ];

            Reading::create($data);
        }

        return $this;
    }

    /**
     * sanitize csv file
     *
     * @param \Webefficiency\Entities\Company $company
     *
     * @return $this
     */
    private function sanitize($company)
    {
        $process = new Process('./a.out storage/fieldlogger/' . $company->fieldlogger_id . '/MemFlash/MemFlash.csv');
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error($process->getErrorOutput());

            return $this;
        }

        if (trim($process->getOutput()) != '') {
            $this->error($process->getOutput());

            return $this;
        }

        return $this;
    }
}
