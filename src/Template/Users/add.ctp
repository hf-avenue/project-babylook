<div class="users form">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?= $this->Form->control('username') ?>
        <input type="hidden" name="twitter_id" value="1234"><!--todo:実際は$_SESSIONに入れて取れよ ダミーコードだぞ、後で消す -->
        <input type="hidden" name="oauth_token" value="hogehogehogehoge"><!--ダミーコードだぞ、後で消す -->
        <input type="hidden" name="oauth_token_secret" value="hogehogehogehoge"><!--ダミーコードだぞ、後で消す -->
        <?= $this->Form->control('user_name') ?>
        <?= $this->Form->control('mail') ?>
        <?= $this->Form->control('password') ?>

    </fieldset>
    <?= $this->Form->button(__('Submit')); ?>
    <?= $this->Form->end() ?>
</div>

<div>
    <div class="col-xs-3">
        <a href="twitter_login" class="btn btn-block btn-lg btn-info">Info Button</a>
    </div>

</div>
