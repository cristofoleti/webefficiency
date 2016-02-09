<?php

namespace Webefficiency\Http\Controllers;

use Datatables;
use Illuminate\Http\Request;

use Webefficiency\Http\Requests;
use Webefficiency\Http\Controllers\Controller;
use Webefficiency\Repositories\UserRepository;
use yajra\Datatables\Html\Builder;

class UsersController extends Controller
{

    /**
     * @var UserInterface
     */
    private $userRepository;

    /**
     * UsersController constructor.
     *
     * @param UserRepository $userRepository
     */
    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Builder $builder
     *
     * @return Response
     */
    public function index(Request $request, Builder $builder)
    {
        if ($request->ajax()) {
            $users = $this->userRepository->all(['id', 'name']);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
