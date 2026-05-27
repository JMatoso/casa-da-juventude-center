<div class="toolbar">
    <a class="btn btn-primary" href="<?= e(url('professores/create')) ?>">+ Novo professor</a>
    <form class="search-form" method="get" action="index.php">
        <input type="hidden" name="r" value="professores/list">
        <input type="text" name="q" value="<?= e($q) ?>" placeholder="Pesquisar">
        <button type="submit" class="btn btn-secondary">Pesquisar</button>
    </form>
</div>

<p style="color:#64748b;margin-bottom:1rem"><?= (int) $total ?> registo(s)</p>

<table class="data-table">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Especialidade</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($items)): ?>
            <tr><td colspan="4">Nenhum professor encontrado.</td></tr>
        <?php else: ?>
            <?php foreach ($items as $row): ?>
                <tr>
                    <td><?= e($row['nome']) ?></td>
                    <td><?= e($row['email'] ?? '—') ?></td>
                    <td><?= e($row['especialidade'] ?? '—') ?></td>
                    <td class="actions-cell">
                        <a class="btn btn-secondary btn-sm" href="<?= e(url('professores/edit', ['id' => $row['id']])) ?>">Editar</a>
                        <?php
                        $deleteRoute = 'professores/delete';
                        $id = (int) $row['id'];
                        $confirmMessage = 'Apagar este professor?';
                        require ADMIN_ROOT . '/views/partials/delete_form.php';
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php
$listRoute = 'professores/list';
require ADMIN_ROOT . '/views/partials/pagination.php';
?>
