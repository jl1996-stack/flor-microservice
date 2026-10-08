<?php
declare(strict_types=1);

use App\Config\Database;
use App\Controllers\FlorController;
use App\Repositories\FlorRepository;
use App\Services\ProveedorExternoService;

require_once __DIR__ . '/../../vendor/autoload.php';

$controller = new FlorController(
    new FlorRepository(Database::connection()),
    new ProveedorExternoService()
);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = rtrim($path, '/') ?: '/';

if ($path === '/flores' && $method === 'GET') {
    $controller->index();
    exit;
}

if ($path === '/flores' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $controller->create($input);
    exit;
}

http_response_code(404);
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['error' => 'Ruta no encontrada'], JSON_UNESCAPED_UNICODE);
