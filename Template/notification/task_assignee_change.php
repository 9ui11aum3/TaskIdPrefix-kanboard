<html>
<body>
<h2><?= $this->text->e($task['title']) ?> (<?= $this->prefix->getPrefix() ?><?= $task['id'] ?>)</h2>

<ul>
    <li>
        <?php if (! empty($task['assignee_username'])): ?>
            <?= t('Assigned to %s', $task['assignee_name'] ?: $task['assignee_username']) ?>
        <?php else: ?>
            <?= t('There is nobody assigned') ?>
        <?php endif ?>
    </li>
</ul>

<?php if (! empty($task['description'])): ?>
    <h3><?= t('Description') ?></h3>
    <?= $this->text->markdown($task['description'], true) ?>
<?php else: ?>
    <p><?= t('There is no description.') ?></p>
<?php endif ?>

<?= $this->render('notification/footer', array('task' => $task)) ?>
</body>
</html>
