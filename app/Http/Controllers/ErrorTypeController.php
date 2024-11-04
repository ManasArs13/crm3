<?php

namespace App\Http\Controllers;

use App\Models\ErrorTypes;
use App\Http\Requests\ErrorTypeRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ErrorTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:error_type')->only(['index', 'filter']);
        $this->middleware('permission:error_type_edit')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $entityName = 'Типы ошибок';
        $urlDelete = 'errorTypes.destroy';
        $urlEdit = 'errorTypes.edit';
        $urlCreate = 'errorTypes.create';

        $entityItems = ErrorTypes::All();

        $selected = [
            "id",
            "name",
            "created_at",
        ];

        foreach ($selected as $column) {

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("errors.type.index", compact(
            'entityItems',
            'entityName',
            "resColumns",
            "urlDelete",
            "urlEdit",
            "urlCreate"
        ));
    }

    public function edit($id)
    {
        $type = ErrorTypes::findOrFail($id);
        return view('errors.type.edit', compact('type'));
    }

    public function update(ErrorTypeRequest $request, $id)
    {
        $type = ErrorTypes::find($id);
        if($type){
            $type->name = $request->name;
            $type->save();
        }
        return redirect()->route('errorTypes.index');
    }

    public function create()
    {
        $responsible = User::All();
        return view('errors.type.create', compact('responsible'));
    }

    public function store(ErrorTypeRequest $request)
    {
        $responsible = User::findOrFail($request->responsible);
        ErrorTypes::Create([ 'name' => $request->name, 'responsible' => $responsible->id ]);
        return redirect()->route('errorTypes.index');
    }

    public function destroy($id)
    {
        return abort(404);
    }

}
