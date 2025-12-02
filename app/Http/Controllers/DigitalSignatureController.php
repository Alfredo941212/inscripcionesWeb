<?php

namespace App\Http\Controllers;

class DigitalSignatureController extends Controller
{
    public function sign()
    {
        $data = "Registro firmado digitalmente";

        $privateKey = file_get_contents(storage_path('app/certs/private.pem'));
        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return [
            'data' => $data,
            'signature' => base64_encode($signature)
        ];
    }

    public function verify()
    {
        $data = "Registro firmado digitalmente";

        $signature = request('signature');

        $publicKey = file_get_contents(storage_path('app/certs/public.pem'));

        $valid = openssl_verify($data, base64_decode($signature), $publicKey, OPENSSL_ALGO_SHA256);

        return [
            'valid_signature' => $valid === 1 ? 'SI' : 'NO'
        ];
    }

    /**
     * MÉTODO NECESARIO PARA EL REGISTRO DE USUARIOS
     */
    public static function signData(string $data): string
    {
        $privateKeyPath = storage_path('app/certs/private.pem');

        if (!file_exists($privateKeyPath)) {
            throw new \Exception("No se encontró la llave privada en: {$privateKeyPath}");
        }

        $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));

        if (!$privateKey) {
            throw new \Exception("No se pudo cargar la llave privada.");
        }

        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($signature);
    }
}
