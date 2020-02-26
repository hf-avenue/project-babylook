<div class="users form">
    <p>ログイン</p>
    <?= $this->Flash->render() ?>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your mail and password') ?></legend>
        <?= $this->Form->control('mail') ?>
        <?= $this->Form->control('password') ?>
    </fieldset>
    <?= $this->Form->button(__('Login')); ?>
    <?= $this->Form->end() ?>
</div>
<br>
<div class="users form">
    <p><a href="../users/add">アカウント新規登録</p>

