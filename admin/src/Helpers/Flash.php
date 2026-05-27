<?php

declare(strict_types=1);

namespace App\Helpers;

final class Flash
{
    public static function success(string $message): void
    {
        $_SESSION['_flash']['success'] = $message;
    }

    public static function error(string $message): void
    {
        $_SESSION['_flash']['error'] = $message;
    }

    /** @return array{success?: string, error?: string} */
    public static function pull(): array
    {
        $messages = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $messages;
    }
}
