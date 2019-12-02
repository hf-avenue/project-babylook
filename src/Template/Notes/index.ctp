<meta charset="UTF-8"/>

<h1>sns  Portal2 proto type system</h1>

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

    <?php foreach ($notes as $note): ?>
        <tr>
            <td><?= $note->id ?></td>
            <td><?= $this->Html->link($note->user_id,['controller' =>'users' ,'action' => 'profile', $note->user_id]) ?></td>
            <td><?= $this->Html->link($note->user->username,['controller' =>'users' ,'action' => 'profile', $note->user_id]) ?></td>
            <td><?= $this->Html->link($note->title, ['action' => 'view', $note->id]) ?></td>
            <td><?= $note->created->format(DATE_RFC850) ?></td>
        </tr>
    <?php endforeach; ?>
</table>