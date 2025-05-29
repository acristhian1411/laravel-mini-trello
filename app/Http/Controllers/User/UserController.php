<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use PhpParser\Node\Expr\Assign;
use Illuminate\Support\Collection;

class UserController extends ApiController
{
    public function index(Request $request)
    {
        try{
            if($request->has('wantsJson') and $request->wantsJson == true){
                $users = User::all();
                return $this->showAll($users);
            }else{
                $users = User::all();
                return Inertia::render('User/Index', [
                    'users' => $users,
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function assignRole(Request $request, $id){
        try{
            $user = User::findOrFail($id);
            $request->validate([
                'role' => 'required|string|exists:roles,name',
            ]);
            $user->assignRole($request->role);
            return $this->showOne($user);
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function AssignPermissionToUser(Request $request, $id){
        try{
            $user = User::findOrFail($id);
            $request->validate([
                'permission' => 'required|string|exists:permissions,name',
            ]);
            $user->givePermissionTo($request->permission);
            return $this->showOne($user);
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function revokePermissionFromUser(Request $request, $id){
        try{
            $user = User::findOrFail($id);
            $request->validate([
                'permission' => 'required|string|exists:permissions,name',
            ]);
            $user->revokePermissionFromUser($request->permission);
            return $this->showOne($user);
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function revokeRoleFromUser(Request $request, $id){
        try{
            $user = User::findOrFail($id);
            $request->validate([
                'role' => 'required|string|exists:roles,name',
            ]);
            $user->removeRole($request->role);
            return $this->showOne($user);
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    public function getPermissionsFromUser(Request $request, $id){
        try{
            $user = User::findOrFail($id);
            $permissions = new Collection(
                $user->getPermissionsViaRoles()
                    ->merge($user->permissions)
                    ->unique('id')
            );
    
            // Solo nombres de los permisos
            $permissionNames = collect($permissions->pluck('name')->values());
            // dd($permissionNames);
            return response()->json($permissionNames);
        }catch(\Exception $e){
            return $this->error($e->getMessage());
        }
    }

}
