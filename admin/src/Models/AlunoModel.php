<?php

declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

final class AlunoModel
{
    public static function count(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) FROM alunos')->fetchColumn();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM alunos WHERE id = ?');
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
            $countStmt = $pdo->prepare('SELECT COUNT(*) FROM alunos WHERE nome_completo LIKE ? OR email LIKE ?');
            $countStmt->execute([$like, $like]);
            $stmt = $pdo->prepare(
                'SELECT * FROM alunos WHERE nome_completo LIKE ? OR email LIKE ? ORDER BY nome_completo ASC LIMIT ? OFFSET ?'
            );
            $stmt->bindValue(1, $like);
            $stmt->bindValue(2, $like);
            $stmt->bindValue(3, $perPage, PDO::PARAM_INT);
            $stmt->bindValue(4, $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $countStmt = $pdo->query('SELECT COUNT(*) FROM alunos');
            $stmt = $pdo->prepare('SELECT * FROM alunos ORDER BY nome_completo ASC LIMIT ? OFFSET ?');
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
            'SELECT id, nome_completo FROM alunos ORDER BY nome_completo ASC'
        )->fetchAll();
    }

  /** @param array<string, mixed> $data */
    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO alunos (nome_completo, email, telefone, data_nascimento, bi, morada) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['nome_completo'],
            $data['email'] ?: null,
            $data['telefone'] ?: null,
            $data['data_nascimento'] ?: null,
            $data['bi'] ?: null,
            $data['morada'] ?: null,
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    /** @param array<string, mixed> $data */
    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE alunos SET nome_completo = ?, email = ?, telefone = ?, data_nascimento = ?, bi = ?, morada = ? WHERE id = ?'
        );
        return $stmt->execute([
            $data['nome_completo'],
            $data['email'] ?: null,
            $data['telefone'] ?: null,
            $data['data_nascimento'] ?: null,
            $data['bi'] ?: null,
            $data['morada'] ?: null,
            $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM alunos WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function hasInscricoes(int $id): bool
    {
        $stmt = Database::connection()->prepare('SELECT COUNT(*) FROM inscricoes WHERE aluno_id = ?');
        $stmt->execute([$id]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
