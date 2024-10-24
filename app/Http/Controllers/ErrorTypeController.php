<?php

namespace App\Http\Controllers;

use App\Models\ErrorTypes;
use App\Http\Requests\ErrorTypeRequest;
use DateTime;
use Illuminate\Http\Request;

class ErrorTypeController extends Controller
{
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
        return view('errors.type.create');
    }

    public function store(ErrorTypeRequest $request)
    {
        ErrorTypes::Create([ 'name' => $request->name ]);
        return redirect()->route('errorTypes.index');
    }

    public function destroy($id)
    {
        $type = ErrorTypes::find($id);
        if($type){
            $type->delete();
        }
        return redirect()->back();
    }

}
