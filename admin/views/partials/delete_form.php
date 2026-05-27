<?php

use App\Helpers\Csrf;

/** @var string $deleteRoute */
/** @var int $id */
/** @var string $confirmMessage */
?>
<form method="post" action="<?= e(url($deleteRoute)) ?>" class="inline-form" data-confirm="<?= e($confirmMessage) ?>">
    <?= Csrf::field() ?>
    <input type="hidden" name="id" value="<?= (int) $id ?>">
    <button type="submit" class="btn btn-danger btn-sm">Apagar</button>
</form>
