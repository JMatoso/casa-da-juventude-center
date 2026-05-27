<?php

declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

final class ProfessorModel
{
    public static function count(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) FROM professores')->fetchColumn();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM professores WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** @return array{items: list<array>, total: int} */
    public static function paginate(int $page, int $perPage, string $q = ''): array
    {
        $offset = ($page - 1) * $perPage;
        $pdo = Database::connection();

        if ($q !== '') {
            $like = '%' . $q . '%';
            $countStmt = $pdo->prepare('SELECT COUNT(*) FROM professores WHERE nome LIKE ? OR email LIKE ?');
            $countStmt->execute([$like, $like]);
            $stmt = $pdo->prepare(
                'SELECT * FROM professores WHERE nome LIKE ? OR email LIKE ? ORDER BY nome ASC LIMIT ? OFFSET ?'
            );
            $stmt->bindValue(1, $like);
            $stmt->bindValue(2, $like);
            $stmt->bindValue(3, $perPage, PDO::PARAM_INT);
            $stmt->bindValue(4, $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $countStmt = $pdo->query('SELECT COUNT(*) FROM professores');
            $stmt = $pdo->prepare('SELECT * FROM professores ORDER BY nome ASC LIMIT ? OFFSET ?');
            $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
        }

        return ['items' => $stmt->fetchAll(), 'total' => (int) $countStmt->fetchColumn()];
    }

    /** @param array<string, mixed> $data */
    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO professores (nome, email, telefone, especialidade) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['nome'],
            $data['email'] ?: null,
            $data['telefone'] ?: null,
            $data['especialidade'] ?: null,
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    /** @param array<string, mixed> $data */
    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE professores SET nome = ?, email = ?, telefone = ?, especialidade = ? WHERE id = ?'
        );
        return $stmt->execute([
            $data['nome'],
            $data['email'] ?: null,
            $data['telefone'] ?: null,
            $data['especialidade'] ?: null,
            $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM professores WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
