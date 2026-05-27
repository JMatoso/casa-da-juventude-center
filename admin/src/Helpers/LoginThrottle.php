<?php

declare(strict_types=1);

namespace App\Helpers;

final class LoginThrottle
{
    private const SESSION_KEY = '_login_attempts';

    public static function isLocked(): bool
    {
        $data = $_SESSION[self::SESSION_KEY] ?? null;
        if (!is_array($data)) {
            return false;
        }
        $max = (int) config('login_max_attempts', 5);
        $lockout = (int) config('login_lockout_seconds', 900);
        if (($data['count'] ?? 0) < $max) {
            return false;
        }
        $lockedUntil = (int) ($data['locked_until'] ?? 0);
        if (time() >= $lockedUntil) {
            unset($_SESSION[self::SESSION_KEY]);
            return false;
        }
        return true;
    }

    public static function recordFailure(): void
    {
        $max = (int) config('login_max_attempts', 5);
        $lockout = (int) config('login_lockout_seconds', 900);
        $data = $_SESSION[self::SESSION_KEY] ?? ['count' => 0, 'locked_until' => 0];
        $data['count'] = (int) ($data['count'] ?? 0) + 1;
        if ($data['count'] >= $max) {
            $data['locked_until'] = time() + $lockout;
            error_log('Admin login locked after ' . $max . ' failed attempts');
        }
        $_SESSION[self::SESSION_KEY] = $data;
    }

    public static function clear(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
    }

    public static function remainingSeconds(): int
    {
        $data = $_SESSION[self::SESSION_KEY] ?? [];
        $lockedUntil = (int) ($data['locked_until'] ?? 0);
        return max(0, $lockedUntil - time());
    }
}
