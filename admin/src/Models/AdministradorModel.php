<?php

declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

final class AdministradorModel
{
    public static function count(): int
    {
        $stmt = Database::connection()->query('SELECT COUNT(*) FROM administradores');
        return (int) $stmt->fetchColumn();
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, nome, email, password_hash FROM administradores WHERE email = ? LIMIT 1'
        );
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(string $nome, string $email, string $passwordHash): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO administradores (nome, email, password_hash) VALUES (?, ?, ?)'
        );
        $stmt->execute([$nome, $email, $passwordHash]);
    }
}
