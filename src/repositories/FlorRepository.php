<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class FlorRepository
{
    public function __construct(private PDO $db) {}

    public function create(string $nombre, string $color, float $precioTallo): array
    {
        $stmt = $this->db->prepare(
            'INSERT INTO VariedadFlor (nombre, color, precio_tallo) VALUES (:nombre, :color, :precio_tallo)'
        );
        $stmt->execute([
            ':nombre' => $nombre,
            ':color' => $color,
            ':precio_tallo' => $precioTallo,
        ]);

        $id = (int)$this->db->lastInsertId();
        return $this->findById($id);
    }

    public function findById(int $id): array
    {
        $stmt = $this->db->prepare('SELECT id, nombre, color, precio_tallo FROM VariedadFlor WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: [];
    }

    public function all(): array
    {
        return $this->db->query(
            'SELECT id, nombre, color, precio_tallo FROM VariedadFlor ORDER BY id'
        )->fetchAll();
    }
}
