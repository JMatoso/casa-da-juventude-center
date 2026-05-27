<?php
/** @var string $listRoute */
/** @var array{page: int, totalPages: int} $meta */
/** @var string $q */
if (($meta['totalPages'] ?? 1) <= 1) {
    return;
}
$prev = max(1, $meta['page'] - 1);
$next = min($meta['totalPages'], $meta['page'] + 1);
$params = $q !== '' ? ['q' => $q] : [];
?>
<div class="pagination">
    <?php if ($meta['page'] > 1): ?>
        <a class="btn btn-secondary btn-sm" href="<?= e(url($listRoute, array_merge($params, ['page' => $prev]))) ?>">Anterior</a>
    <?php endif; ?>
    <span>Página <?= (int) $meta['page'] ?> de <?= (int) $meta['totalPages'] ?></span>
    <?php if ($meta['page'] < $meta['totalPages']): ?>
        <a class="btn btn-secondary btn-sm" href="<?= e(url($listRoute, array_merge($params, ['page' => $next]))) ?>">Seguinte</a>
    <?php endif; ?>
</div>
