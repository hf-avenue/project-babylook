<meta charset="UTF-8"/>

<h1>Babylook Portal2 proto type system</h1>

<!-- テキストリンクで投稿ページに -->
<?= $this->Html->link('Add Article', ['action' => 'add']) ?>

<!-- 画像リンクで投稿ページに -->
<?= $this->Html->image(
    'Upload_32.png',
    array('url' => array('controller' => 'Articles', 'action' => 'add')));
?>

<table>
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
                <?= $this->Html->link($article->title, ['action' => 'view', $article->id]) ?>
            </td>
            <td>
                <?= $article->created->format(DATE_RFC850) ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>