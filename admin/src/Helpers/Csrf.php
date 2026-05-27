<?php

declare(strict_types=1);

namespace App\Helpers;

final class Csrf
{
    public static function token(): string
    {
        $key = config('csrf_key', '_csrf_token');
        if (empty($_SESSION[$key])) {
            $_SESSION[$key] = bin2hex(random_bytes(32));
        }
        return $_SESSION[$key];
    }

    public static function field(): string
    {
        $token = self::token();
        return '<input type="hidden" name="_csrf" value="' . e($token) . '">';
    }

    public static function validate(?string $token): bool
    {
        $key = config('csrf_key', '_csrf_token');
        $sessionToken = $_SESSION[$key] ?? '';
        if ($token === null || $sessionToken === '') {
            return false;
        }
        return hash_equals($sessionToken, $token);
    }

    public static function requireValid(string $redirectRoute = 'dashboard'): void
    {
        if (!self::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            Flash::error('Pedido inválido. Tente novamente.');
            redirect($redirectRoute);
        }
    }
}
