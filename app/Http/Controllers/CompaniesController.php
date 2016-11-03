<?php

namespace Webefficiency\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Webefficiency\Http\Requests;
use Webefficiency\Http\Controllers\Controller;

use Webefficiency\Http\Requests\CompanyRequest;
use Webefficiency\Company;
use Webefficiency\Group;


class CompaniesController extends Controller
{
    
    public function __construct() {
       
    }
        
    public function company($group_id, $company_id)
    {
        return view('companies.company');
    }

    public function companyUsers($company_id)
    {

        $company = Company::find($company_id);
        $users = $company->users()->get();

        return view('users.list', [
            'users' => $users,
            'company' => $company
            ]);
    }

   
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $companies = Company::orderBy('name')->get();
        return view('companies.list', [
            'companies' => $companies,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->middleware('group_admin');
        

        $groups = Group::where('active',1)->get();

        return view('companies.form', [ 
            'groups'=>$groups
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CompanyResquest  $request
     * @return Response
     */
    public function store(CompanyRequest $request)
    {
        $this->middleware('group_admin');
        

        $obj = Company::create([
            'name'=>$request->name, 
            'group_id'=>$request->group_id, 
            'fieldlogger_id'=>$request->fieldlogger_id, 
            //'active'=>($request->active ? 1 : 0 )
            'active'=>1
        ]);
        return $obj;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $this->middleware('group_admin');
        

        $company = Company::find($id);
        $groups = Group::where('active',1)->get();

        return view('companies.form', [ 
            'groups'=> $groups,
            'obj' => $company
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  CompanyResquest  $request
     * @return Response
     */
    public function update($id, CompanyRequest $request)
    {
        $this->middleware('group_admin');
        

        $obj = Company::find($id)->update([
            'name'=>$request->name, 
            'group_id'=>$request->group_id, 
            'fieldlogger_id'=>$request->fieldlogger_id, 
            //'active'=>($request->active ? 1 : 0 )
        ]);

        return '1';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->middleware('group_admin');
        

        $obj = Company::find($id);
        $obj->delete();
    }
}
