<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SecurityProtocolsController extends Controller
{
    public function index()
    {
        $results = [];

        // FTPS (simulación académica)
        $results['ftps'] = "FTPS simulado: transferencia segura cifrada mediante SSL/TLS.";

        // SSH (simulación)
        $results['ssh'] = "SSH simulado: protocolo de acceso seguro a servidores.";

        // SCP/SFTP (simulación)
        $results['scp'] = "SCP/SFTP simulado: transferencia segura de archivos.";

        // IMAPS
        $results['imaps'] = "IMAPS: Puerto 993, cifrado SSL/TLS (explicación académica).";

        // IPSEC
        $results['ipsec'] = "IPSec protege tráfico a nivel red (VPNs). No aplicable en hosting compartido.";

        // SET
        $results['set'] = "SET (Secure Electronic Transaction). Estándar de pago seguro ya obsoleto.";

        // OAuth / Sanctum
        $results['oauth'] = "OAuth simulado mediante tokens seguros de Laravel Sanctum.";

        return view('admin.protocolos.index', compact('results'));
    }
}
