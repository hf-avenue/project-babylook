<div class="users form">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>

        <?= $this->Form->control('user_name') ?>
        <?= $this->Form->control('mail') ?>
        <?= $this->Form->control('password') ?>

    </fieldset>
    <?= $this->Form->button(__('Submit')); ?>
    <?= $this->Form->end() ?>
</div>

<div>


</div>
