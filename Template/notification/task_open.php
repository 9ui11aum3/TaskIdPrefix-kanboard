<html>
<body>
<h2><?= $this->text->e($task['title']) ?> (<?= $this->prefix->getPrefix() ?><?= $task['id'] ?>)</h2>

<p><?php
    $msg = t('The task #%d has been opened.', $task['id']);
    echo str_replace('#' . $task['id'], $this->prefix->getPrefix() . $task['id'], $msg);
?></p>

<?= $this->render('notification/footer', array('task' => $task)) ?>
</body>
</html>
