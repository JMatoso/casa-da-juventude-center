<?php

use App\Helpers\Csrf;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Administração</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body class="login-page">
<div class="login-box">
    <h1>Casa da Juventude Center</h1>
    <p style="text-align:center;color:#64748b;margin-bottom:1.5rem">Área de administração</p>

    <?php if (!empty($flash['error'])): ?>
        <div class="alert alert-error"><?= e($flash['error']) ?></div>
    <?php endif; ?>

    <?php if (!empty($locked)): ?>
        <div class="alert alert-error">
            Conta temporariamente bloqueada. Tente novamente em <?= (int) ($remaining ?? 0) ?> segundos.
        </div>
    <?php else: ?>
        <form method="post" action="<?= e(url('login/authenticate')) ?>">
            <?= Csrf::field() ?>
            <div class="form-group" style="margin-bottom:1rem">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autocomplete="username">
            </div>
            <div class="form-group" style="margin-bottom:1rem">
                <label for="password">Palavra-passe</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Entrar</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
