<?php

namespace App\Http\Controllers\Boards;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Collect;
use App\Models\Boards;
use App\Http\Controllers\Boards\BoardsController;
// use PDF;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class BoardsReportController extends Controller
{
    public function generarReporte()
    {

        $registros = Boards::all(); // o de la DB

        $conImagen = $registros->whereNotNull('image')->count();
        $sinImagen = $registros->whereNull('image')->count();
        $title = 'Reporte de registros';
        $date = date('Y-m-d H:i:s');
        $pdf = PDF::loadView('reports.boards', [
            'registros' => $registros,
            'title'=> $title,
            'date' => $date,
        ]);
        return Response::make($pdf->output(),200,[
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="reporte.pdf"',
        ]);
        // return $pdf->stream('reporte.pdf');
    }
}
