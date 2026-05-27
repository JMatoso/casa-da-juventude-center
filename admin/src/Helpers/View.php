<?php

declare(strict_types=1);

namespace App\Helpers;

final class View
{
    public static function render(string $name, array $data = [], ?string $layout = 'admin'): void
    {
        $viewFile = ADMIN_ROOT . '/views/' . str_replace('.', '/', $name) . '.php';
        if (!is_file($viewFile)) {
            http_response_code(500);
            echo 'View não encontrada.';
            return;
        }

        extract($data, EXTR_SKIP);
        $flash = Flash::pull();

        ob_start();
        require $viewFile;
        $content = ob_get_clean() ?: '';

        if ($layout === null) {
            echo $content;
            return;
        }

        $layoutFile = ADMIN_ROOT . '/views/layouts/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            echo $content;
            return;
        }

        require $layoutFile;
    }
}
