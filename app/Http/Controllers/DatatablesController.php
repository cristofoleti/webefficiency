<?php

namespace Webefficiency\Http\Controllers;

use Datatables;
use Illuminate\Http\Request;

use Webefficiency\Http\Requests;
use Webefficiency\Http\Controllers\Controller;

class DatatablesController extends Controller
{
    public function data($repo_name)
    {
        $repo = app()->make('Webefficiency\Repositories\Contracts\\' . ucfirst($repo_name) . 'Interface');

        return Datatables::of($repo->all())
            /*->setTransformer('Webefficiency\Transformers\DataTables\ReadingsTransformer')*/
            ->make(true);
    }
}
