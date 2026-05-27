<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

use App\Auth\Session;
use App\Controllers\AlunoController;
use App\Controllers\CursoController;
use App\Controllers\DashboardController;
use App\Controllers\FuncionarioController;
use App\Controllers\InscricaoController;
use App\Controllers\LoginController;
use App\Controllers\ProfessorController;
use App\Middleware\RequireAuth;
use App\Services\AdminSeeder;

try {
    App\Database::connection();
    AdminSeeder::seedIfEmpty();
} catch (Throwable $e) {
    if (config('env') === 'local') {
        http_response_code(500);
        echo '<h1>Erro de ligação à base de dados</h1>';
        echo '<p>Verifique o ficheiro .env e execute schema.sql e seed.sql.</p>';
        if (ini_get('display_errors')) {
            echo '<pre>' . e($e->getMessage()) . '</pre>';
        }
        exit;
    }
    error_log('Bootstrap DB error: ' . $e->getMessage());
    http_response_code(503);
    echo 'Serviço temporariamente indisponível.';
    exit;
}

$route = $_GET['r'] ?? (Session::isLoggedIn() ? 'dashboard' : 'login');
$route = is_string($route) ? trim($route) : 'dashboard';

$publicRoutes = [
    'login' => [LoginController::class, 'show'],
];

$postRoutes = [
    'login/authenticate' => [LoginController::class, 'authenticate'],
    'logout' => [LoginController::class, 'logout'],
    'alunos/store' => [AlunoController::class, 'store'],
    'alunos/update' => [AlunoController::class, 'update'],
    'alunos/delete' => [AlunoController::class, 'delete'],
    'cursos/store' => [CursoController::class, 'store'],
    'cursos/update' => [CursoController::class, 'update'],
    'cursos/delete' => [CursoController::class, 'delete'],
    'professores/store' => [ProfessorController::class, 'store'],
    'professores/update' => [ProfessorController::class, 'update'],
    'professores/delete' => [ProfessorController::class, 'delete'],
    'funcionarios/store' => [FuncionarioController::class, 'store'],
    'funcionarios/update' => [FuncionarioController::class, 'update'],
    'funcionarios/delete' => [FuncionarioController::class, 'delete'],
    'inscricoes/store' => [InscricaoController::class, 'store'],
    'inscricoes/update' => [InscricaoController::class, 'update'],
    'inscricoes/delete' => [InscricaoController::class, 'delete'],
];

$protectedRoutes = [
    'dashboard' => [DashboardController::class, 'index'],
    'alunos/list' => [AlunoController::class, 'list'],
    'alunos/create' => [AlunoController::class, 'create'],
    'alunos/edit' => [AlunoController::class, 'edit'],
    'cursos/list' => [CursoController::class, 'list'],
    'cursos/create' => [CursoController::class, 'create'],
    'cursos/edit' => [CursoController::class, 'edit'],
    'professores/list' => [ProfessorController::class, 'list'],
    'professores/create' => [ProfessorController::class, 'create'],
    'professores/edit' => [ProfessorController::class, 'edit'],
    'funcionarios/list' => [FuncionarioController::class, 'list'],
    'funcionarios/create' => [FuncionarioController::class, 'create'],
    'funcionarios/edit' => [FuncionarioController::class, 'edit'],
    'inscricoes/list' => [InscricaoController::class, 'list'],
    'inscricoes/create' => [InscricaoController::class, 'create'],
    'inscricoes/edit' => [InscricaoController::class, 'edit'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($postRoutes[$route])) {
    if ($route !== 'login/authenticate') {
        RequireAuth::handle();
    }
    [$class, $method] = $postRoutes[$route];
    (new $class())->$method();
    exit;
}

if ($route === 'login' && Session::isLoggedIn()) {
    redirect('dashboard');
}

if (isset($publicRoutes[$route])) {
    [$class, $method] = $publicRoutes[$route];
    (new $class())->$method();
    exit;
}

if (!isset($protectedRoutes[$route])) {
    http_response_code(404);
    echo 'Página não encontrada.';
    exit;
}

RequireAuth::handle();
[$class, $method] = $protectedRoutes[$route];
(new $class())->$method();
