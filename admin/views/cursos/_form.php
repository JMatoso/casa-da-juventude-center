<?php

use App\Helpers\Csrf;

/** @var array|null $item */
$isEdit = !empty($item);
$checked = true;
if (isset($_SESSION['_old'])) {
    $checked = isset($_SESSION['_old']['ativo']);
} elseif ($isEdit) {
    $checked = !empty($item['ativo']);
}
?>
<form method="post" action="<?= e(url($isEdit ? 'cursos/update' : 'cursos/store')) ?>">
    <?= Csrf::field() ?>
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
    <?php endif; ?>
    <div class="form-grid">
        <div class="form-group">
            <label for="nome">Nome do curso *</label>
            <input type="text" id="nome" name="nome" required maxlength="180"
                   value="<?= e(field_value('nome', $item)) ?>">
        </div>
        <div class="form-group">
            <label for="duracao">Duração *</label>
            <input type="text" id="duracao" name="duracao" required maxlength="80"
                   value="<?= e(field_value('duracao', $item)) ?>">
        </div>
        <div class="form-group">
            <label for="preco_kz">Preço (Kz) *</label>
            <input type="number" id="preco_kz" name="preco_kz" required min="0" step="0.01"
                   value="<?= e(field_value('preco_kz', $item)) ?>">
        </div>
        <div class="form-group">
            <label for="periodo">Período *</label>
            <input type="text" id="periodo" name="periodo" required maxlength="80"
                   value="<?= e(field_value('periodo', $item)) ?>">
        </div>
        <div class="form-group">
            <label for="horario">Horário *</label>
            <input type="text" id="horario" name="horario" required maxlength="80"
                   value="<?= e(field_value('horario', $item)) ?>">
        </div>
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="ativo" value="1" <?= $checked ? 'checked' : '' ?>>
                Curso ativo
            </label>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Guardar' : 'Criar' ?></button>
        <a class="btn btn-secondary" href="<?= e(url('cursos/list')) ?>">Cancelar</a>
    </div>
</form>
