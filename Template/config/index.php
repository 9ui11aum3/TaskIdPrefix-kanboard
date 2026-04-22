<?php echo $this->layout('config/layout', array('title' => t('Task ID Prefix'), 'values' => $values, 'errors' => $errors)) ?>

<div class="page-header">
    <h2><?= t('Task ID Prefix') ?></h2>
</div>

<form method="post" action="<?= $this->url->to('ConfigController', 'save', array(), 'TaskIdPrefix') ?>">
    <?= $this->form->csrf() ?>

    <div class="form-group">
        <?= $this->form->label(t('Prefix character(s)'), 'taskidprefix_prefix') ?>
        <?= $this->form->text(
            'taskidprefix_prefix',
            $values,
            $errors,
            array('placeholder' => 'n°', 'maxlength' => '20', 'tabindex' => '1')
        ) ?>
        <p class="form-help">
            <?= t('Replaces the "#" character before task IDs in email bodies and web notifications. Leave blank to restore the default (n°).') ?>
        </p>
    </div>

    <div class="form-actions">
        <input type="submit" value="<?= t('Save') ?>" class="btn btn-blue" tabindex="2" />
        <?= $this->url->link(t('Cancel'), 'ConfigController', 'index', array(), false, 'btn btn-default', '', false, 'TaskIdPrefix') ?>
    </div>
</form>
