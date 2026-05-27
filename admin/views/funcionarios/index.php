<div class="toolbar">
    <a class="btn btn-primary" href="<?= e(url('funcionarios/create')) ?>">+ Novo funcionário</a>
    <form class="search-form" method="get" action="index.php">
        <input type="hidden" name="r" value="funcionarios/list">
        <input type="text" name="q" value="<?= e($q) ?>" placeholder="Pesquisar">
        <button type="submit" class="btn btn-secondary">Pesquisar</button>
    </form>
</div>

<p style="color:#64748b;margin-bottom:1rem"><?= (int) $total ?> registo(s)</p>

<table class="data-table">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Cargo</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($items)): ?>
            <tr><td colspan="4">Nenhum funcionário encontrado.</td></tr>
        <?php else: ?>
            <?php foreach ($items as $row): ?>
                <tr>
                    <td><?= e($row['nome']) ?></td>
                    <td><?= e($row['cargo']) ?></td>
                    <td><?= e($row['email'] ?? '—') ?></td>
                    <td class="actions-cell">
                        <a class="btn btn-secondary btn-sm" href="<?= e(url('funcionarios/edit', ['id' => $row['id']])) ?>">Editar</a>
                        <?php
                        $deleteRoute = 'funcionarios/delete';
                        $id = (int) $row['id'];
                        $confirmMessage = 'Apagar este funcionário?';
                        require ADMIN_ROOT . '/views/partials/delete_form.php';
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php
$listRoute = 'funcionarios/list';
require ADMIN_ROOT . '/views/partials/pagination.php';
?>
