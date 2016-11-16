<?php namespace Webefficiency\Http\Controllers;

use Webefficiency\Company;

class HomeController extends Controller
{
    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        /** @var \Illuminate\Database\Eloquent\Collection $companies */
        $companies_all = \Auth::user()->companies;
        $companies = \Auth::user()->companies()->select('id','name')->get();
        //remove Group Admin companies
        foreach ($companies_all as $key => $company) {
            if($company->group->is_admin) {
                unset($companies_all[$key]);
                unset($companies[$key]);
            }
        }

        return view('home', compact('companies'));
    }

    public function processFile()
    {
        $companies = Company::where('fieldlogger_url', '<>', '')->get();

        foreach ($companies as $company) {

            $handle = curl_init($company->fieldlogger_url . '/hash/' . $company->fieldlogger_id);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_exec($handle);
            $http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

            if (200 !== (int) $http_code) {
                continue;
            }

            $response = json_decode(file_get_contents($company->fieldlogger_url . '/hash/' . $company->fieldlogger_id));

            $checksum = $response->checksum;

            if (! \File::exists(storage_path('fieldlogger/' . $company->fieldlogger_id . '/MemFlash'))) {
                \File::makeDirectory(storage_path('fieldlogger/' . $company->fieldlogger_id . '/MemFlash'), 0775, true);
            }

            $buffer = 1048576;
            $ret = 0;
            $fin = fopen($company->fieldlogger_url . '/' . $company->fieldlogger_id . '/MemFlash/MemFlash.csv', "rb");
            $fout = fopen(storage_path('fieldlogger/' . $company->fieldlogger_id . '/MemFlash') . '/MemFlash.csv', "w");
            while (! feof($fin)) {
                $ret += fwrite($fout, fread($fin, $buffer));
            }
            fclose($fin);
            fclose($fout);

            if ((md5_file(storage_path('fieldlogger/' . $company->fieldlogger_id . '/MemFlash') . '/MemFlash.csv') === $checksum)) {
                \Artisan::call('webef:import');
            }
        }

        return 'ok!';
    }
}
