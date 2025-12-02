<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use Illuminate\Http\Request;

class FirmaController extends Controller
{
    public function index()
    {
        return view('admin.firma.index');
    }

    public function verificar(Request $request)
    {
        $request->validate([
            'data' => 'required',
            'signature' => 'required',
        ]);

        $publicKey = file_get_contents(storage_path('app/certs/public.pem'));

        $valid = openssl_verify(
            $request->data,
            base64_decode($request->signature),
            $publicKey,
            OPENSSL_ALGO_SHA256
        );

        // Registrar en blockchain
        Block::createBlock("Verificación de firma. Resultado: " . ($valid ? "VÁLIDA" : "NO VÁLIDA"));

        return back()->with('resultado', $valid ? "Firma válida ✔" : "Firma inválida ❌");
    }
}
