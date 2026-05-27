<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Auth\Session;
use App\Helpers\Csrf;
use App\Helpers\Flash;
use App\Helpers\LoginThrottle;
use App\Models\AdministradorModel;

final class LoginController
{
    public function show(): void
    {
        if (Session::isLoggedIn()) {
            redirect('dashboard');
        }
        view('login', ['locked' => LoginThrottle::isLocked(), 'remaining' => LoginThrottle::remainingSeconds()], null);
    }

    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('login');
        }
        Csrf::requireValid('login');

        if (LoginThrottle::isLocked()) {
            Flash::error('Muitas tentativas. Aguarde ' . LoginThrottle::remainingSeconds() . ' segundos.');
            redirect('login');
        }

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $admin = AdministradorModel::findByEmail($email);
        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            LoginThrottle::recordFailure();
            error_log('Failed admin login attempt for email: ' . $email);
            Flash::error('Credenciais inválidas.');
            redirect('login');
        }

        LoginThrottle::clear();
        Session::login((int) $admin['id'], $admin['nome'], $admin['email']);
        redirect('dashboard');
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::requireValid('dashboard');
        }
        Session::logout();
        redirect('login');
    }
}
