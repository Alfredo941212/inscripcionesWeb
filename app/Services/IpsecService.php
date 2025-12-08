<?php

namespace App\Services;

use App\Models\IpsecLog;

class IpsecService
{
    public function simulateTunnel()
    {
        // Simulación de datos IPSEC
        $data = [
            "mode"       => "Tunnel Mode",
            "encryption" => "AES-256",
            "auth"       => "SHA-256",
            "ip_origen"  => request()->ip(),
            "ip_destino" => "192.168.1.1",
            "resultado"  => "Túnel IPSEC establecido correctamente"
        ];

        // Guardar en BD
        IpsecLog::create($data);

        return $data;
    }
}
