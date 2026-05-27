<div class="cards">
    <div class="card">
        <div class="number"><?= (int) ($stats['alunos'] ?? 0) ?></div>
        <div class="label">Alunos</div>
    </div>
    <div class="card">
        <div class="number"><?= (int) ($stats['cursos'] ?? 0) ?></div>
        <div class="label">Cursos ativos</div>
    </div>
    <div class="card">
        <div class="number"><?= (int) ($stats['professores'] ?? 0) ?></div>
        <div class="label">Professores</div>
    </div>
    <div class="card">
        <div class="number"><?= (int) ($stats['funcionarios'] ?? 0) ?></div>
        <div class="label">Funcionários</div>
    </div>
    <div class="card">
        <div class="number"><?= (int) ($stats['inscricoes'] ?? 0) ?></div>
        <div class="label">Inscrições</div>
    </div>
</div>

<div class="panel">
    <h3>Acesso rápido</h3>
    <div class="quick-links">
        <a class="btn btn-primary btn-sm" href="<?= e(url('alunos/create')) ?>">+ Aluno</a>
        <a class="btn btn-primary btn-sm" href="<?= e(url('cursos/create')) ?>">+ Curso</a>
        <a class="btn btn-primary btn-sm" href="<?= e(url('professores/create')) ?>">+ Professor</a>
        <a class="btn btn-primary btn-sm" href="<?= e(url('funcionarios/create')) ?>">+ Funcionário</a>
        <a class="btn btn-primary btn-sm" href="<?= e(url('inscricoes/create')) ?>">+ Inscrição</a>
    </div>
</div>

<div class="panel">
    <h3>Últimas inscrições</h3>
    <?php if (empty($recentInscricoes)): ?>
        <p style="color:#64748b">Ainda não existem inscrições registadas.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Curso</th>
                    <th>Data</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentInscricoes as $row): ?>
                    <tr>
                        <td><?= e($row['aluno_nome']) ?></td>
                        <td><?= e($row['curso_nome']) ?></td>
                        <td><?= e($row['data_inscricao']) ?></td>
                        <td><span class="badge badge-<?= e($row['status']) ?>"><?= e($row['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
