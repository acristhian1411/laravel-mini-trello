<?php

namespace App\Http\Controllers\Role;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\ApiController;

class RoleController extends ApiController
{
    public function index(Request $request){
        try{
            $roles = Role::with('permissions')->get();
            return $this->showAll($roles);
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }
    public function store(Request $request){
        try{
            $rules = [
                'name' => 'required|string|unique:roles,name',
                'permissions' => 'array',
                'permissions.*' => 'string|exists:permissions,name',
            ];
            $request->validate($rules);
            $role = Role::create($request->all());
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
            return $this->showOne($role);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    public function show(Request $request, $id){
        try{

            $role = Role::with('permissions')->findOrFail($id);
            if($request->has('wantsJson') and $request->wantsJson == true){
                return $this->showOne($role);
            }else{
                return Inertia::render('Role/Index', [
                    'roles' => Role::all(),
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function update(Request $request, $id){
        try{
            $role = Role::findOrFail($id);
            $request->validate([
                'name' => 'sometimes|string|unique:roles,name,' . $role->id,
                'permissions' => 'array',
                'permissions.*' => 'string|exists:permissions,name',
            ]);
    
            if ($request->has('name')) {
                $role->name = $request->name;
                $role->save();
            }
    
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
            return $this->showOne($role);
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }
    public function destroy($id){
        try{
            $role = Role::findOrFail($id);
            $role->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Role deleted successfully',
            ], 200);
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function syncPermissions(Request $request, $id){
        try{
            $role = Role::findOrFail($id);
            $request->validate([
                'permissions' => 'array',
                'permissions.*' => 'string|exists:permissions,name',
            ]);
            $role->syncPermissions($request->permissions);
            return $this->showOne($role);
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }
    /**
     * Obtener la lista de permisos asignados a un role
     * @param \Illuminate\Http\Request $request
     */
    public function getPermissions(Request $request, $id){
        try{
            $role = Role::findOrFail($id);
            $permissions = $role->getPermissionsViaRoles();
            return $this->showAll($permissions);
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function revokePermissionFromRole(Request $request, $id){
        try{
            $role = Role::findOrFail($id);
            $request->validate([
                'permissions' => 'array',
                'permissions.*' => 'string|exists:permissions,name',
            ]);
            $role->revokePermissionFromRole($request->permissions);
            return $this->showOne($role);
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }
}
