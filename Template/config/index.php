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
            array('placeholder' => 'n°', 'maxlength' => '20')
        ) ?>
        <p class="form-help">
            <?= t('Replaces the "#" character before task IDs in email notifications and the web notification panel. Leave blank to use the default: n°') ?>
        </p>
    </div>

    <div class="form-actions">
        <input type="submit" value="<?= t('Save') ?>" class="btn btn-blue" />
    </div>
</form>
