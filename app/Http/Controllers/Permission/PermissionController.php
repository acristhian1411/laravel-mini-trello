<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;

class PermissionController extends ApiController
{
    public function index(Request $request)
    {
        try{
            if($request->has('wantsJson') and $request->wantsJson == true){
                $permissions = Permission::all();
                return $this->showAll($permissions);
            }else{
                $permissions = Permission::all();
                return Inertia::render('Permission/Index', [
                    'permissions' => $permissions,
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'guard_name' => 'required|string|max:255',
            ]);
            $permission = Permission::create(['name' => $request->name,'guard_name' => $request->guard_name]);
            if($request->has('wantsJson') and $request->wantsJson == true){
                return $this->showOne($permission);
            }else{
                return Inertia::render('Permission/Index', [
                    'permissions' => Permission::all(),
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function update(Request $request, Permission $permission)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'guard_name' => 'required|string|max:255',
            ]);
            $permission->update(['name' => $request->name,'guard_name' => $request->guard_name]);
            if($request->has('wantsJson') and $request->wantsJson == true){
                return $this->showOne($permission);
            }else{
                return Inertia::render('Permission/Index', [
                    'permissions' => Permission::all(),
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function destroy(Request $request, int $id)
    {
        try{
            $permission = Permission::find($id);
            if($permission == null){
                return response()->json([
                    'message' => 'Permission not found',
                ], 404);
            }
            $permission->delete();
            if($request->has('wantsJson') and $request->wantsJson == true){
                return $this->showOne($permission);
            }else{
                return Inertia::render('Permission/Index', [
                    'permissions' => Permission::all(),
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
