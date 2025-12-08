<?php

namespace App\Services;

class FtpsService
{
    public function simulateFtpsUpload()
    {
        return [
            "server" => "ftps://inscripción.test",
            "port" => 990,
            "status" => "Conexión FTPS simulada correctamente.",
            "action" => "Archivo enviado mediante canal seguro TLS.",
            "timestamp" => now()->toDateTimeString()
        ];
    }
}
