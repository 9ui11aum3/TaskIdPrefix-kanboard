<html>
<body>
<h2><?= $this->text->e($task['title']) ?> (<?= $this->prefix->getPrefix() ?><?= $task['id'] ?>)</h2>

<ul>
    <li><?= t('Title:') ?> <?= $this->text->e($subtask['title']) ?></li>
    <li><?= t('Status:') ?> <?= t($subtask['status_name']) ?></li>
    <li><?= t('Assignee:') ?> <?= $this->text->e($subtask['name'] ?: $subtask['username'] ?: '?') ?></li>
    <?php if (! empty($subtask['time_estimated'])): ?>
        <li><?= t('Time tracking:') ?> <?= n($subtask['time_estimated']) ?> <?= t('hours') ?></li>
    <?php endif ?>
</ul>

<?= $this->render('notification/footer', array('task' => $task)) ?>
</body>
</html>
