<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function index()
    {
        $path = storage_path('logs/laravel-json.log');

        if (!File::exists($path)) {
            return response()->json(['message' => 'Archivo de log no encontrado.'], 404);
        }

        // Leemos las últimas líneas (opcional: podrías paginar)
        $lines = File::lines($path)->reverse()->take(100)->values();

        $logs = [];

        foreach ($lines as $line) {
            $decoded = json_decode($line, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $logs[] = $decoded;
            }
        }

        return response()->json($logs);
    }
}
