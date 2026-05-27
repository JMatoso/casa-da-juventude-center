<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $route, array $params = []): string
{
    $config = require ADMIN_ROOT . '/config/app.php';
    $base = $config['url'] !== '' ? $config['url'] : '';
    $params = array_merge(['r' => $route], $params);
    $query = http_build_query($params);
    return ($base !== '' ? $base : 'index.php') . '?' . $query;
}

function redirect(string $route, array $params = []): never
{
    header('Location: ' . url($route, $params));
    exit;
}

function view(string $name, array $data = [], ?string $layout = 'admin'): void
{
    App\Helpers\View::render($name, $data, $layout);
}

function config(string $key, mixed $default = null): mixed
{
    static $app = null;
    if ($app === null) {
        $app = require ADMIN_ROOT . '/config/app.php';
    }
    return $app[$key] ?? $default;
}

function old(string $key, string $default = ''): string
{
    return e($_SESSION['_old'][$key] ?? $default);
}

function set_old(array $data): void
{
    $_SESSION['_old'] = $data;
}

function clear_old(): void
{
    unset($_SESSION['_old']);
}

function field_value(string $key, ?array $item = null, string $default = ''): string
{
    if (isset($_SESSION['_old'][$key])) {
        return (string) $_SESSION['_old'][$key];
    }
    if ($item !== null && isset($item[$key])) {
        return (string) $item[$key];
    }
    return $default;
}
