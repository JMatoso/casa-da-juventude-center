<?php

declare(strict_types=1);

namespace App\Services;

use App\Database;
use App\Models\AdministradorModel;

final class AdminSeeder
{
    public static function seedIfEmpty(): void
    {
        if (AdministradorModel::count() > 0) {
            return;
        }

        $email = $_ENV['ADMIN_SEED_EMAIL'] ?? '';
        $password = $_ENV['ADMIN_SEED_PASSWORD'] ?? '';
        $nome = $_ENV['ADMIN_SEED_NAME'] ?? 'Administrador';

        if ($email === '' || $password === '') {
            return;
        }

        try {
            Database::connection();
        } catch (\Throwable) {
            return;
        }

        AdministradorModel::create($nome, $email, password_hash($password, PASSWORD_DEFAULT));
        error_log('Admin inicial criado para: ' . $email);
    }
}
