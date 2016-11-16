<?php

namespace Webefficiency\Http\Controllers;

use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Webefficiency\Http\Requests;
use Webefficiency\Http\Controllers\Controller;
//use Webefficiency\Repositories\UserRepository;
//use yajra\Datatables\Html\Builder;

use Webefficiency\Http\Requests\UserRequest;
use Webefficiency\Company;
use Webefficiency\User;
use Webefficiency\Group;

class UsersController extends Controller
{

    /**
     * @var UserInterface
     */
    //private $userRepository;

    /**
     * UsersController constructor.
     *
     * @param UserRepository $userRepository
     */
    // function __construct(UserRepository $userRepository)
    // {
    //     $this->userRepository = $userRepository;
    // }
    
    public function __construct() {
       $this->middleware('admin');
    }

    public function index(){
        $users = [];
        $auth_user_group_admin = Auth::user()->isGroupAdmin();
        $companies = Auth::user()->companies;

        //usuarios das empresas do usuário logado
        foreach ($companies as $company) {
            foreach ($company->users as $user) {
                $users[] = $user;
            }
        }

        //remove duplicados
        $users = array_unique($users);

        //check se os usuários estão em grupo admin
        foreach ($users as $key => $user) {
            foreach ($user->companies as $user_company) {
                if($user_company->group->is_admin && !$auth_user_group_admin){
                    unset($users[$key]);
                }
            }
        }

        return view('users.list', [
            'users' => $users
            ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Builder $builder
     *
     * @return Response
     */
    //public function index2(Request $request, Builder $builder)
    public function index2(Request $request)
    {
        if ($request->ajax()) {
            //$users = $this->userRepository->all(['id', 'name']);
            $users = User::all(['id','name']);
            return Datatables::of($users)
                ->addColumn('checkbox', function($user) {
                    return "<input type='checkbox' id='{$user->id}'><label for='{$user->id}'></label>";
                })
                ->addColumn('action', function($user) {
                    $routeEdit = route('users.edit', $user->id);

                    return "
                      <a href='{$routeEdit}' class='waves-effect waves-teal btn-flat'><i class='material-icons'>mode_edit</i></a>";
                })
                ->make(true);
        }

        $datatables = $builder
            ->parameters([
                'searching' => false,
                'lengthChange' => false,
                'order' => [
                    [1, 'desc']
                ]
            ])
            ->ajax(route('users.index'))
            ->addCheckbox(['title' => "<input type='checkbox' id='check_all'><label for='check_all'></label>"])
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID', 'width' => 100])
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nome'])
            ->addColumn([
                'title' => '',
                'data' => 'action',
                'name' => 'action',
                'width' => 60,
                'className' => 'text-right',
                'sortable' => false,
                'orderable' => false,
                'searchable' => false
            ]);

        return view('users.index', compact('datatables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $companies = (Auth::user()->isGroupAdmin()) ? Company::all() : Auth::user()->companies;

        return view('users.form', [ 
            'companies'=>$companies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $request
     * @return Response
     */
    public function store(UserRequest $request)
    {
        $obj = User::create([
            'name'=>$request->name, 
            'email'=>$request->email, 
            'password'=>bcrypt($request->password),
            'is_admin'=> ($request->is_admin ? 1 : 0 ),
        ]);

        $companies = Input::get('company_id');
        $obj->companies()->attach($companies);

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
        $user = User::find($id);
        $companies = (Auth::user()->isGroupAdmin()) ? Company::all() : Auth::user()->companies;

        //companies id related to user
        $user_companies = $user->companies()->get();
        foreach ($user_companies as $key => $value) {
            $ids_user_companies[] = $value->id;
        }

        return view('users.form', [ 
            'companies'=> $companies,
            'obj' => $user,
            'user_companies' => $ids_user_companies
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  UserRequest  $request
     * @return Response
     */
    public function update($id, UserRequest $request)
    {
        $user = User::find($id);

        $user->update([
            'name'=>$request->name,
            'is_admin'=>($request->is_admin ? 1 : 0 ),
        ]);

        if(isset($request->new_password) && !empty($request->new_password)){
            $user->update([
                'password'=>bcrypt($request->new_password)
            ]);
        }

        $user->companies()->detach();
        $companies = Input::get('company_id');
        $user->companies()->attach($companies);        

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
        $obj = User::find($id);
        $obj->delete();
    }

}
