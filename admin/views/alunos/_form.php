<?php

use App\Helpers\Csrf;

/** @var array|null $item */
$isEdit = !empty($item);
?>
<form method="post" action="<?= e(url($isEdit ? 'alunos/update' : 'alunos/store')) ?>">
    <?= Csrf::field() ?>
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
    <?php endif; ?>
    <div class="form-grid">
        <div class="form-group">
            <label for="nome_completo">Nome completo *</label>
            <input type="text" id="nome_completo" name="nome_completo" required maxlength="180"
                   value="<?= e(field_value('nome_completo', $item)) ?>">
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
            <label for="data_nascimento">Data de nascimento</label>
            <input type="date" id="data_nascimento" name="data_nascimento"
                   value="<?= e(field_value('data_nascimento', $item)) ?>">
        </div>
        <div class="form-group">
            <label for="bi">BI</label>
            <input type="text" id="bi" name="bi" maxlength="50"
                   value="<?= e(field_value('bi', $item)) ?>">
        </div>
        <div class="form-group" style="grid-column:1/-1">
            <label for="morada">Morada</label>
            <input type="text" id="morada" name="morada" maxlength="255"
                   value="<?= e(field_value('morada', $item)) ?>">
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Guardar' : 'Criar' ?></button>
        <a class="btn btn-secondary" href="<?= e(url('alunos/list')) ?>">Cancelar</a>
    </div>
</form>
