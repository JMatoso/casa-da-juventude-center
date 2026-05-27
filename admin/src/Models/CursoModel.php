<?php

declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

final class CursoModel
{
    public static function countActive(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) FROM cursos WHERE ativo = 1')->fetchColumn();
    }

    public static function count(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) FROM cursos')->fetchColumn();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM cursos WHERE id = ?');
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
            $countStmt = $pdo->prepare('SELECT COUNT(*) FROM cursos WHERE nome LIKE ?');
            $countStmt->execute([$like]);
            $stmt = $pdo->prepare(
                'SELECT * FROM cursos WHERE nome LIKE ? ORDER BY nome ASC LIMIT ? OFFSET ?'
            );
            $stmt->bindValue(1, $like);
            $stmt->bindValue(2, $perPage, PDO::PARAM_INT);
            $stmt->bindValue(3, $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $countStmt = $pdo->query('SELECT COUNT(*) FROM cursos');
            $stmt = $pdo->prepare('SELECT * FROM cursos ORDER BY nome ASC LIMIT ? OFFSET ?');
            $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
        }

        return ['items' => $stmt->fetchAll(), 'total' => (int) $countStmt->fetchColumn()];
    }

    /** @return list<array> */
    public static function allForSelect(): array
    {
        return Database::connection()->query(
            'SELECT id, nome FROM cursos WHERE ativo = 1 ORDER BY nome ASC'
        )->fetchAll();
    }

    /** @param array<string, mixed> $data */
    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO cursos (nome, duracao, preco_kz, periodo, horario, ativo) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['nome'],
            $data['duracao'],
            $data['preco_kz'],
            $data['periodo'],
            $data['horario'],
            $data['ativo'] ? 1 : 0,
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    /** @param array<string, mixed> $data */
    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE cursos SET nome = ?, duracao = ?, preco_kz = ?, periodo = ?, horario = ?, ativo = ? WHERE id = ?'
        );
        return $stmt->execute([
            $data['nome'],
            $data['duracao'],
            $data['preco_kz'],
            $data['periodo'],
            $data['horario'],
            $data['ativo'] ? 1 : 0,
            $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM cursos WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function hasInscricoes(int $id): bool
    {
        $stmt = Database::connection()->prepare('SELECT COUNT(*) FROM inscricoes WHERE curso_id = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
