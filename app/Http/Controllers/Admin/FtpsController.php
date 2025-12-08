<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Services\FtpsService;

class FtpsController extends Controller
{
    public function index(FtpsService $ftps)
    {
        $resultado = $ftps->simulateFtpsUpload();
        return view('protocolos.ftps', compact('resultado'));
    }

      // ← MÉTODO QUE TE FALTABA
    public function send(FtpsService $ftps)
    {
        $resultado = $ftps->simulateFtpsUpload();

        // Regresa al dashboard con mensaje
        return back()->with('ftps', $resultado);
    }
}
