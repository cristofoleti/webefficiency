<?php


namespace Webefficiency\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Webefficiency\Http\Requests;
use Webefficiency\Http\Controllers\Controller;

use Webefficiency\Http\Requests\GroupRequest;
use Webefficiency\Group;

class GroupsController extends Controller
{
    
    public function __construct() {
       $this->middleware('group_admin');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $groups = Group::orderBy('name')->get();

        return view('groups.list', [
            'groups' => $groups,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('groups.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(GroupRequest $request)
    {
        $obj = Group::create([
            'name'=>$request->name, 
            //'active'=>($request->active ? 1 : 0 ),
            //'is_admin'=>($request->is_admin ? 1 : 0 )
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
        $group = Group::find($id);

        return view('groups.form', [ 
            'obj' => $group
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, GroupRequest $request)
    {
        $obj = Group::find($id)->update([
            'name'=>$request->name, 
            'active'=>1,
            //'active'=>($request->active ? 1 : 0 ),
            //'is_admin'=>($request->is_admin ? 1 : 0 )
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
        $obj = Group::find($id);
        $obj->delete();
    }
}
