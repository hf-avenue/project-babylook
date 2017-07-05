<meta charset="UTF-8"/>

<h1>Babylook Portal2 proto type system</h1>

<!-- テキストリンクで投稿ページに -->
<?= $this->Html->link('投稿する', ['action' => 'add']) ?>

<table>
    <tr>
        <th>Id</th>
        <th>投稿者Id</th>
        <th>投稿者名</th>
        <th>作品Title</th>
        <th>Created</th>
    </tr>

    <?php foreach ($novels as $novel): ?>
        <tr>
            <td><?= $novel->id ?></td>
            <td><?= $this->Html->link($novel->user_id,['controller' =>'users' ,'action' => 'profile', $novel->user_id]) ?></td>
            <td><?= $this->Html->link($novel->user->username,['controller' =>'users' ,'action' => 'profile', $novel->user_id]) ?></td>
            <td><?= $this->Html->link($novel->title, ['action' => 'view', $novel->id]) ?></td>
            <td><?= $novel->created->format(DATE_RFC850) ?></td>
        </tr>
    <?php endforeach; ?>
</table>