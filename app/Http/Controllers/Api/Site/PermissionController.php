<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function update(Request $request){
        $role = Role::findOrFail($request->role_id);
        $permission = Permission::findOrFail($request->permission_id);

        if ($request->assign && $request->assign == 'true') {
            $role->givePermissionTo($permission);
            return response()->json(['message' => 'Permission assigned']);
        } else {
            $role->revokePermissionTo($permission);
            return response()->json(['message' => 'Permission revoked']);
        }
    }
}
