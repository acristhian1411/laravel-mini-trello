<?php

namespace App\Http\Controllers\Lists;

use App\Models\Lists;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Inertia\Inertia;

class ListsController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->wantsJson){
            try{
                $t = Lists::query()->first();
                $query = Lists::query();
                $query = $this->filterData($query, $t);
                $datos = $query->get();
                return $this->showAll($datos);
            }catch(\Exception $e){
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        }else{
            return Inertia::render('Lists/Index',[
                'lists' => Lists::all()
            ]);
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
                'board_id' => 'required',
            ];
            $request->validate($rules);
            $list = Lists::create($request->all());
            if ($request->wantsJson) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Registro creado correctamente',
                    'data' => $list,
                ], 200);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'Error al crear el tablero',
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
    public function show(Request $request, int $id)
    {
        try{
            $list = Lists::findOrFail($id);
            if ($request->wantsJson) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Registro creado correctamente',
                    'data' => $list,
                ], 200);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'Error al crear el tablero',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lists $lists)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try{
            $rules = [
                'name' => 'required',
                'board_id' => 'required',
            ];
            $request->validate($rules);
            $board = Lists::findOrFail($id);
            $board->update($request->all());
            if(request()->wantsJson){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Registro actualizado correctamente',
                    'data' => $board,
                ], 200);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'Error al actualizar el tablero',
            ], 500);
        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->errors(),
                'message' => 'Los datos no son correctos',
            ], 422);
        }
    }

    public function search(Request $request)
    {
        try{
            $boards = Lists::where('name', 'ilike', '%' . $request->search . '%')->get();
            if(request()->wantsJson){
                return response()->json([
                    'status' => 'success',
                    'data' => $boards,
                ], 200);
            }
            return ['data' => $boards];
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'Error al buscar los tableros',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try{
            $board = Lists::findOrFail($id);
            $board->delete();
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
                'message' => 'Error al eliminar el tablero',
            ], 500);
        }
    }
}
