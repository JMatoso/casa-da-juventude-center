<div class="toolbar">
    <a class="btn btn-primary" href="<?= e(url('cursos/create')) ?>">+ Novo curso</a>
    <form class="search-form" method="get" action="index.php">
        <input type="hidden" name="r" value="cursos/list">
        <input type="text" name="q" value="<?= e($q) ?>" placeholder="Pesquisar por nome">
        <button type="submit" class="btn btn-secondary">Pesquisar</button>
    </form>
</div>

<p style="color:#64748b;margin-bottom:1rem"><?= (int) $total ?> registo(s)</p>

<table class="data-table">
    <thead>
        <tr>
            <th>Curso</th>
            <th>Duração</th>
            <th>Preço (Kz)</th>
            <th>Período</th>
            <th>Ativo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($items)): ?>
            <tr><td colspan="6">Nenhum curso encontrado.</td></tr>
        <?php else: ?>
            <?php foreach ($items as $row): ?>
                <tr>
                    <td><?= e($row['nome']) ?></td>
                    <td><?= e($row['duracao']) ?></td>
                    <td><?= e(number_format((float) $row['preco_kz'], 0, ',', '.')) ?></td>
                    <td><?= e($row['periodo']) ?></td>
                    <td><?= !empty($row['ativo']) ? 'Sim' : 'Não' ?></td>
                    <td class="actions-cell">
                        <a class="btn btn-secondary btn-sm" href="<?= e(url('cursos/edit', ['id' => $row['id']])) ?>">Editar</a>
                        <?php
                        $deleteRoute = 'cursos/delete';
                        $id = (int) $row['id'];
                        $confirmMessage = 'Apagar este curso?';
                        require ADMIN_ROOT . '/views/partials/delete_form.php';
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php
$listRoute = 'cursos/list';
require ADMIN_ROOT . '/views/partials/pagination.php';
?>
