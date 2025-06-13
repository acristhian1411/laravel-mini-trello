<?php

namespace App\Http\Controllers\Boards;
use App\Http\Controllers\ApiController;
use App\Models\Boards;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use G4T\Swagger\Attributes\SwaggerSection;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\BoardStoreRequest;
// use 
#[SwaggerSection('Boards', 'Boards')]
class BoardsController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        if($request->wantsJson == true){
            try{
                $t = Boards::query()->first();
                $query = Boards::query();
                $query = $this->filterData($query, $t);
                $datos = $query->get();
                $datos->transform(function ($board) {
                    if ($board->image) {
                        $board->image_url = \URL::temporarySignedRoute(
                            'boards.secure-image',
                            now()->addMinutes(30),
                            ['path' => $board->image]
                        );
                    } else {
                        $board->image_url = null;
                    }
                    return $board;
                });
                return $this->showAll($datos);
                
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
    public function store(BoardStoreRequest $request)
    {
        try{
            
            $path = null;
            Log::info('hasFile?', ['has' => $request->hasFile('image')]);
            if ($request->hasFile('image')) {
                try{
                    Log::info('entra a if');
                    // dump($request);
                    $file = $request->file('image');
                    $filename = \Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('/', $filename, 'boards'); // guarda en storage/app/private/boards
                }catch(\Exception $e){
                    Log::error($e->getMessage());
                }
                // dump($path);
            }
            // dump()
            $board = Boards::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'image'=>$path,
                
            ]);
            if ($request->wantsJson) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Registro creado correctamente',
                    'data' => $board,
                ], 200);
            }
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'Error al crear el tablero',
            ], 500);
        }catch(\Illuminate\Validation\ValidationException $e){
            Log::error($e->getMessage());
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
            if(request()->wantsJson){
                return response()->json([
                    'status' => 'success',
                    'data' => $board,
                ], 200);
            }else{
                return Inertia::render('Boards/Show',[
                    'board' => $board
                ]);
            }
        }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'No se encontró el tablero',
            ], 404);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'message' => 'Error al mostrar el tablero',
            ], 500);
        }
    }

    /**
     * This function is used to search boards by description
     */
    public function search(Request $request)
    {
        try{
            $boards = Boards::where('description', 'ilike', '%' . $request->search . '%')->get();
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try{
            $board = Boards::findOrFail($id);
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
