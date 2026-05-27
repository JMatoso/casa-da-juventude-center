<?php

declare(strict_types=1);

return [
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'url' => rtrim($_ENV['APP_URL'] ?? '', '/'),
    'session_name' => 'cjc_admin_session',
    'csrf_key' => '_csrf_token',
    'per_page' => 20,
    'login_max_attempts' => 5,
    'login_lockout_seconds' => 900,
];
