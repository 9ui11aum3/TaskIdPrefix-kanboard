<html>
<body>
<h2><?= $this->text->e($task['title']) ?> (<?= $this->prefix->getPrefix() ?><?= $task['id'] ?>)</h2>

<ul>
    <li><?= t('Project:') ?> <?= $this->text->e($task['project_name']) ?></li>
    <li><?= t('Created:') ?> <?= $this->dt->datetime($task['date_creation']) ?></li>
    <?php if (! empty($task['date_due'])): ?>
        <li><?= t('Due date:') ?> <?= $this->dt->datetime($task['date_due']) ?></li>
    <?php endif ?>
    <?php if (! empty($task['creator_name'])): ?>
        <li><?= t('Creator:') ?> <?= $this->text->e($task['creator_name']) ?></li>
    <?php endif ?>
    <?php if ($task['assignee_username']): ?>
        <li><?= t('Assigned to:') ?> <?= $this->text->e($task['assignee_name'] ?: $task['assignee_username']) ?></li>
    <?php else: ?>
        <li><?= t('There is nobody assigned') ?></li>
    <?php endif ?>
    <li><?= t('Column:') ?> <?= $this->text->e($task['column_title']) ?></li>
    <li><?= t('Position:') ?> <?= $this->text->e($task['position']) ?></li>
    <?php if (! empty($task['category_name'])): ?>
        <li><?= t('Category:') ?> <?= $this->text->e($task['category_name']) ?></li>
    <?php endif ?>
</ul>

<?php if (! empty($task['description'])): ?>
    <h2><?= t('Description') ?></h2>
    <?= $this->text->markdown($task['description'], true) ?: t('There is no description.') ?>
<?php endif ?>

<?= $this->render('notification/footer', array('task' => $task)) ?>
</body>
</html>
