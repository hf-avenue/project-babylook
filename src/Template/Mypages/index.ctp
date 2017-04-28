<meta charset="UTF-8"/>

<h1>Babylook Portal2 proto type system</h1>
<?= $this->Html->link('Add Article', ['controller' =>'articles' ,'action' => 'add']) ?>
<table>
    <tr>
        <th>あなたがイイネされた回数は</th>
        <th><?=$my_score ?>回です</th>
    </tr>

    <tr>
        <th>あなたがイイネした回数は</th>
        <th><?=$exam_score ?>回です</th>
    </tr>

    <tr>
        <th>Id</th>
        <th>投稿者Id</th>
        <th>作品Title</th>
        <th>Created</th>
    </tr>



    <?php foreach ($articles as $article): ?>
        <tr>
            <td><?= $article->id ?></td>
            <td><?= $article->user_id ?></td>
            <td>
                <?= $this->Html->link($article->title, ['controller' =>'articles' ,'action' => 'view', $article->id]) ?>
            </td>
            <td>
                <?= $article->created->format(DATE_RFC850) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>