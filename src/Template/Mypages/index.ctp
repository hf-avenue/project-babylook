<meta charset="UTF-8"/>
<div class="demo demo5">
    <div class="heading"><span>マイページ</span></div>
</div>
<table>
    <tr>
        <th><h5>あなたの名前：</h5></th>
        <th><h5><?= $users->user_name ?></h5></th>
    </tr>
    <tr>
        <th><?= $this->Html->image('/img/default_icon.jpg'); ?></th>
    </tr>
    <tr>
        <th><h6><?= $this->Html->link('プロフィール更新', ['action' => 'edit']) ?></h6></th>
        <th><h6><?= $this->Html->link('ノートを投稿', ['controller' =>'notes' ,'action' => 'add']) ?></h6></th>

    </tr>

    <tr>
        <th>あなたがイイネされた回数は</th>
        <th><?=$my_score ?>回です</th>
    </tr>

    <tr>
        <th>あなたがイイネした回数は</th>
        <th><?=$exam_score ?>回です</th>
    </tr>

    <tr>
        <th><h5>プロフィール</h5></th>
        <th>
            <?php if($profile->body){
                echo nl2br(h($profile->body));
            } ?>

        </th>
    </tr>
</table>
