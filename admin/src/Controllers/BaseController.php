<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Csrf;

abstract class BaseController
{
    protected string $resource;
    protected string $routePrefix;
    protected string $singularLabel;

    protected function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }
        Csrf::requireValid('dashboard');
    }

    protected function idFromQuery(): ?int
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        return $id && $id > 0 ? $id : null;
    }

    protected function pageFromQuery(): int
    {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        return $page && $page > 0 ? $page : 1;
    }

    protected function searchQuery(): string
    {
        $q = $_GET['q'] ?? '';
        return is_string($q) ? trim($q) : '';
    }

    protected function perPage(): int
    {
        return (int) config('per_page', 20);
    }

    /** @return array{page: int, perPage: int, totalPages: int} */
    protected function paginationMeta(int $total, int $page): array
    {
        $perPage = $this->perPage();
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        return ['page' => $page, 'perPage' => $perPage, 'totalPages' => $totalPages];
    }
}
