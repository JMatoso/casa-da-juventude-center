<?php

use App\Helpers\Csrf;

/** @var array|null $item */
$isEdit = !empty($item);
?>
<form method="post" action="<?= e(url($isEdit ? 'professores/update' : 'professores/store')) ?>">
    <?= Csrf::field() ?>
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
    <?php endif; ?>
    <div class="form-grid">
        <div class="form-group">
            <label for="nome">Nome *</label>
            <input type="text" id="nome" name="nome" required maxlength="180"
                   value="<?= e(field_value('nome', $item)) ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" maxlength="180"
                   value="<?= e(field_value('email', $item)) ?>">
        </div>
        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" maxlength="40"
                   value="<?= e(field_value('telefone', $item)) ?>">
        </div>
        <div class="form-group">
            <label for="especialidade">Especialidade</label>
            <input type="text" id="especialidade" name="especialidade" maxlength="180"
                   value="<?= e(field_value('especialidade', $item)) ?>">
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Guardar' : 'Criar' ?></button>
        <a class="btn btn-secondary" href="<?= e(url('professores/list')) ?>">Cancelar</a>
    </div>
</form>
