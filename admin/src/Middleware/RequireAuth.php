<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Auth\Session;

final class RequireAuth
{
    public static function handle(): void
    {
        if (!Session::isLoggedIn()) {
            redirect('login');
        }
    }
}
