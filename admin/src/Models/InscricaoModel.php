<?php

declare(strict_types=1);

namespace App\Models;

use App\Database;
use PDO;

final class InscricaoModel
{
    public const STATUSES = ['pendente', 'ativa', 'concluida', 'cancelada'];

    public static function count(): int
    {
        return (int) Database::connection()->query('SELECT COUNT(*) FROM inscricoes')->fetchColumn();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT i.*, a.nome_completo AS aluno_nome, c.nome AS curso_nome
             FROM inscricoes i
             INNER JOIN alunos a ON a.id = i.aluno_id
             INNER JOIN cursos c ON c.id = i.curso_id
             WHERE i.id = ?'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** @return array{items: list<array>, total: int} */
    public static function paginate(int $page, int $perPage, string $q = ''): array
    {
        $offset = ($page - 1) * $perPage;
        $pdo = Database::connection();
        $base = 'FROM inscricoes i
                 INNER JOIN alunos a ON a.id = i.aluno_id
                 INNER JOIN cursos c ON c.id = i.curso_id';

        if ($q !== '') {
            $like = '%' . $q . '%';
            $countStmt = $pdo->prepare("SELECT COUNT(*) $base WHERE a.nome_completo LIKE ? OR c.nome LIKE ?");
            $countStmt->execute([$like, $like]);
            $stmt = $pdo->prepare(
                "SELECT i.*, a.nome_completo AS aluno_nome, c.nome AS curso_nome
                 $base WHERE a.nome_completo LIKE ? OR c.nome LIKE ?
                 ORDER BY i.data_inscricao DESC, i.id DESC LIMIT ? OFFSET ?"
            );
            $stmt->bindValue(1, $like);
            $stmt->bindValue(2, $like);
            $stmt->bindValue(3, $perPage, PDO::PARAM_INT);
            $stmt->bindValue(4, $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $countStmt = $pdo->query("SELECT COUNT(*) $base");
            $stmt = $pdo->prepare(
                "SELECT i.*, a.nome_completo AS aluno_nome, c.nome AS curso_nome
                 $base ORDER BY i.data_inscricao DESC, i.id DESC LIMIT ? OFFSET ?"
            );
            $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
        }

        return ['items' => $stmt->fetchAll(), 'total' => (int) $countStmt->fetchColumn()];
    }

    /** @return list<array> */
    public static function latest(int $limit = 10): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT i.*, a.nome_completo AS aluno_nome, c.nome AS curso_nome
             FROM inscricoes i
             INNER JOIN alunos a ON a.id = i.aluno_id
             INNER JOIN cursos c ON c.id = i.curso_id
             ORDER BY i.created_at DESC LIMIT ?'
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function hasActiveDuplicate(int $alunoId, int $cursoId, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM inscricoes
                WHERE aluno_id = ? AND curso_id = ? AND status IN ('pendente', 'ativa')";
        $params = [$alunoId, $cursoId];
        if ($excludeId !== null) {
            $sql .= ' AND id != ?';
            $params[] = $excludeId;
        }
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    /** @param array<string, mixed> $data */
    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO inscricoes (aluno_id, curso_id, data_inscricao, status, observacoes) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['aluno_id'],
            $data['curso_id'],
            $data['data_inscricao'],
            $data['status'],
            $data['observacoes'] ?: null,
        ]);
        return (int) Database::connection()->lastInsertId();
    }

    /** @param array<string, mixed> $data */
    public static function update(int $id, array $data): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE inscricoes SET aluno_id = ?, curso_id = ?, data_inscricao = ?, status = ?, observacoes = ? WHERE id = ?'
        );
        return $stmt->execute([
            $data['aluno_id'],
            $data['curso_id'],
            $data['data_inscricao'],
            $data['status'],
            $data['observacoes'] ?: null,
            $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM inscricoes WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
