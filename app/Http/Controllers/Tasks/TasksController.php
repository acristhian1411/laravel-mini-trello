<?php

namespace App\Http\Controllers\Tasks;

use App\Models\Tasks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
class TaskController extends Controller
class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $tasks = Tasks::all();
            if ($request->wantsJson) {
                return response()->json([
                'status' => 'success',
                'data' => $tasks,
            ], 200);
            }else{
                return Inertia::render('Tasks/Index',[
                    'tasks' => $tasks
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $rules = [
                'name' => 'required',
                'description' => 'required',
            ];
            $request->validate($rules);
            $task = Tasks::create($request->all());
            if ($request->wantsJson) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Registro creado correctamente',
                    'data' => $task,
                ], 200);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'Error al crear la tarea',
            ], 500);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->errors(),
                'message' => 'Los datos no son correctos',
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try{
            $task = Tasks::findOrFail($id);
            if(request()->wantsJson){
                return response()->json([
                    'status' => 'success',
                    'data' => $task,
                ], 200);
            }else{
                return Inertia::render('Tasks/Show',[
                    'task' => $task
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'Error al mostrar la tarea',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try{
            $task = Tasks::findOrFail($id);
            $task->update($request->all());
            if(request()->wantsJson){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Registro actualizado correctamente',
                    'data' => $task,
                ], 200);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'Error al actualizar la tarea',
            ], 500);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->errors(),
                'message' => 'Los datos no son correctos',
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try{
            $task = Tasks::findOrFail($id);
            $task->delete();
            if(request()->wantsJson){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Registro eliminado correctamente',
                ], 200);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'Error al eliminar la tarea',
            ], 500);
        }
    }
}
