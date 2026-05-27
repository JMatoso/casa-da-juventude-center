<?php

use App\Auth\Session;
use App\Helpers\Csrf;

$assetBase = 'assets';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Admin') ?> — Casa da Juventude</title>
    <link rel="stylesheet" href="<?= e($assetBase) ?>/css/admin.css">
</head>
<body>
<div class="admin-layout">
    <aside class="sidebar">
        <h1>Casa da Juventude<br><small style="font-weight:normal;opacity:.8">Administração</small></h1>
        <nav>
            <a href="<?= e(url('dashboard')) ?>" class="<?= ($activeNav ?? '') === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
            <a href="<?= e(url('alunos/list')) ?>" class="<?= ($activeNav ?? '') === 'alunos' ? 'active' : '' ?>">Alunos</a>
            <a href="<?= e(url('cursos/list')) ?>" class="<?= ($activeNav ?? '') === 'cursos' ? 'active' : '' ?>">Cursos</a>
            <a href="<?= e(url('professores/list')) ?>" class="<?= ($activeNav ?? '') === 'professores' ? 'active' : '' ?>">Professores</a>
            <a href="<?= e(url('funcionarios/list')) ?>" class="<?= ($activeNav ?? '') === 'funcionarios' ? 'active' : '' ?>">Funcionários</a>
            <a href="<?= e(url('inscricoes/list')) ?>" class="<?= ($activeNav ?? '') === 'inscricoes' ? 'active' : '' ?>">Inscrições</a>
        </nav>
        <form method="post" action="<?= e(url('logout')) ?>" style="margin-top:2rem">
            <?= Csrf::field() ?>
            <button type="submit" class="btn btn-secondary" style="width:100%">Sair</button>
        </form>
    </aside>
    <main class="main">
        <div class="topbar">
            <h2><?= e($pageTitle ?? '') ?></h2>
            <div class="user-info"><?= e(Session::adminNome()) ?></div>
        </div>

        <?php if (!empty($flash['success'])): ?>
            <div class="alert alert-success"><?= e($flash['success']) ?></div>
        <?php endif; ?>
        <?php if (!empty($flash['error'])): ?>
            <div class="alert alert-error"><?= e($flash['error']) ?></div>
        <?php endif; ?>

        <?= $content ?>
    </main>
</div>
<script src="<?= e($assetBase) ?>/js/admin.js" defer></script>
</body>
</html>
