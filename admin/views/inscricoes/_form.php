<?php

use App\Helpers\Csrf;

/** @var array|null $item */
/** @var list<array> $alunos */
/** @var list<array> $cursos */
/** @var list<string> $statuses */
$isEdit = !empty($item);
$selectedAluno = field_value('aluno_id', $item);
$selectedCurso = field_value('curso_id', $item);
$selectedStatus = field_value('status', $item, 'pendente');
$defaultDate = field_value('data_inscricao', $item, date('Y-m-d'));
?>
<form method="post" action="<?= e(url($isEdit ? 'inscricoes/update' : 'inscricoes/store')) ?>">
    <?= Csrf::field() ?>
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
    <?php endif; ?>
    <div class="form-grid">
        <div class="form-group">
            <label for="aluno_id">Aluno *</label>
            <select id="aluno_id" name="aluno_id" required>
                <option value="">— Selecionar —</option>
                <?php foreach ($alunos as $a): ?>
                    <option value="<?= (int) $a['id'] ?>" <?= (string) $a['id'] === $selectedAluno ? 'selected' : '' ?>>
                        <?= e($a['nome_completo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="curso_id">Curso *</label>
            <select id="curso_id" name="curso_id" required>
                <option value="">— Selecionar —</option>
                <?php foreach ($cursos as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= (string) $c['id'] === $selectedCurso ? 'selected' : '' ?>>
                        <?= e($c['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="data_inscricao">Data da inscrição *</label>
            <input type="date" id="data_inscricao" name="data_inscricao" required
                   value="<?= e($defaultDate) ?>">
        </div>
        <div class="form-group">
            <label for="status">Estado *</label>
            <select id="status" name="status" required>
                <?php foreach ($statuses as $status): ?>
                    <option value="<?= e($status) ?>" <?= $status === $selectedStatus ? 'selected' : '' ?>>
                        <?= e($status) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="grid-column:1/-1">
            <label for="observacoes">Observações</label>
            <textarea id="observacoes" name="observacoes"><?= e(field_value('observacoes', $item)) ?></textarea>
        </div>
    </div>
    <?php if (empty($alunos) || empty($cursos)): ?>
        <p class="alert alert-error">Registe pelo menos um aluno e um curso ativo antes de criar inscrições.</p>
    <?php endif; ?>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary" <?= (empty($alunos) || empty($cursos)) ? 'disabled' : '' ?>>
            <?= $isEdit ? 'Guardar' : 'Criar' ?>
        </button>
        <a class="btn btn-secondary" href="<?= e(url('inscricoes/list')) ?>">Cancelar</a>
    </div>
</form>
