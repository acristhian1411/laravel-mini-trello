<?php

namespace App\Http\Controllers\Boards;

use App\Models\Boards;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->wantsJson()){
            try{
                $boards = Boards::all();
                return response()->json([
                'status' => 'success',
                'data' => $boards,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        }else{
            return Inertia::render('Boards/Index',[
                'boards' => Boards::all()
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
                'description' => 'required',
            ];
            $request->validate($rules);
            $board = Boards::create($request->all());
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
    public function show(int $id)
    {
        try{
            $board = Boards::findOrFail($id);
            if(request()->wantsJson()){
                return response()->json([
                    'status' => 'success',
                    'data' => $board,
                ], 200);
            }else{
                return Inertia::render('Boards/Show',[
                    'board' => $board
                ]);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'Error al mostrar el tablero',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Boards $boards)
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
                'description' => 'required',
            ];
            $request->validate($rules);
            $board = Boards::findOrFail($id);
            $board->update($request->all());
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try{
            $board = Boards::findOrFail($id);
            $board->delete();
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'Error al eliminar el tablero',
            ], 500);
        }
    }
}
