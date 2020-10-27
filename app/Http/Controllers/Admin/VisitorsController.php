<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyVisitorRequest;
use App\Http\Requests\StoreVisitorRequest;
use App\Http\Requests\UpdateVisitorRequest;
use App\Models\Departement;
use App\Models\Governorate;
use App\Models\Visitor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
class VisitorsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('visitor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $visitors = Visitor::all();

        return view('admin.visitors.index', compact('visitors'));
    }

    public function create()
    {
        abort_if(Gate::denies('visitor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
//         $user = Auth::user();
//        $governorates = $user->team->governorates;
//        echo $governorates;
//        die();
//        foreach($gov as $g)
//        {
//            echo $g->governorate_name;
//        }
//        die();

        
        $user = Auth::user();
        $governorates = $user->team->governorates()->pluck('governorate_name', 'id')->prepend(trans('global.pleaseSelect'), '');

       // ->whereIn('id', [1, 2, 3])
//         $governorates = Governorate::all()->pluck('governorate_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        // $governorates = Governorate::whereIn('id',$governorates)->pluck('governorate_name', 'id')->prepend(trans('global.pleaseSelect'), '');
//dd($governorates);
        $depts = Departement::all()->pluck('dept_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.visitors.create', compact('governorates', 'depts'));
    }

    public function store(StoreVisitorRequest $request)
    {
        $visitor = Visitor::create($request->all());

        return redirect()->route('admin.visitors.index');
    }

    public function edit(Visitor $visitor)
    {
        abort_if(Gate::denies('visitor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $governorates = Governorate::all()->pluck('governorate_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $depts = Departement::all()->pluck('dept_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $visitor->load('governorate', 'dept', 'team');

        return view('admin.visitors.edit', compact('governorates', 'depts', 'visitor'));
    }

    public function update(UpdateVisitorRequest $request, Visitor $visitor)
    {
        $visitor->update($request->all());

        return redirect()->route('admin.visitors.index');
    }

    public function show(Visitor $visitor)
    {
        abort_if(Gate::denies('visitor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $visitor->load('governorate', 'dept', 'team');

        return view('admin.visitors.show', compact('visitor'));
    }

    public function destroy(Visitor $visitor)
    {
        abort_if(Gate::denies('visitor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $visitor->delete();

        return back();
    }

    public function massDestroy(MassDestroyVisitorRequest $request)
    {
        Visitor::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
