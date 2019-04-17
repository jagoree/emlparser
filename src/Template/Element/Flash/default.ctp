<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="<?= h($class) ?>" onclick="this.classList.add('hidden');"><?= $message ?></div>
<div class="row">
    Новых проектов: <?= $params['projects']; ?><br>
    Новых пользователей: <?= $params['users']; ?><br>
    Всего постов добавлено: <?= $params['posts']; ?>
</div>
