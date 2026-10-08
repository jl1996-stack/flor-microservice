<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\FlorRepository;
use App\Services\ProveedorExternoService;
use InvalidArgumentException;
use Throwable;

final class FlorController
{
    public function __construct(
        private FlorRepository $repository,
        private ProveedorExternoService $externalService
    ) {}

    public function create(array $input): void
    {
        try {
            $nombre = trim((string)($input['nombre'] ?? ''));
            $color = trim((string)($input['color'] ?? ''));
            $precio = $input['precio_tallo'] ?? null;

            if ($nombre === '' || $color === '' || !is_numeric($precio) || (float)$precio < 0) {
                throw new InvalidArgumentException('Los campos nombre, color y precio_tallo son obligatorios; precio_tallo debe ser numérico y no negativo.');
            }

            $flor = $this->repository->create($nombre, $color, (float)$precio);
            $this->json(['message' => 'Flor creada correctamente', 'data' => $flor], 201);
        } catch (InvalidArgumentException $e) {
            $this->json(['error' => $e->getMessage()], 422);
        } catch (Throwable $e) {
            $this->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function index(): void
    {
        try {
            $flores = $this->repository->all();
            $descripcion = $this->externalService->obtenerDescripcionAgronomica();

            foreach ($flores as &$flor) {
                $flor['descripcion_agronomica'] = $descripcion;
            }
            unset($flor);

            $this->json(['data' => $flores]);
        } catch (Throwable $e) {
            $this->json(['error' => $e->getMessage()], 502);
        }
    }

    private function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
