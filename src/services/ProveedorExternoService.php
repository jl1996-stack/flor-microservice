<?php
declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class ProveedorExternoService
{
    private const URL = 'https://jsonplaceholder.typicode.com/posts/1';

    public function obtenerDescripcionAgronomica(): string
    {
        $ch = curl_init(self::URL);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $body = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($body === false || $status < 200 || $status >= 300) {
            throw new RuntimeException('No fue posible consultar el proveedor externo: ' . ($error ?: 'HTTP ' . $status));
        }

        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        return (string)($data['body'] ?? '');
    }
}
