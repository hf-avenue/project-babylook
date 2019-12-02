<meta charset="UTF-8"/>

<h1>sns  Portal2 proto type system</h1>

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
        <th>投稿者名</th>
        <th>作品Title</th>
        <th>サムネイル</th>
        <th>Created</th>
    </tr>

    <?php foreach ($articles as $article): ?>
        <tr>
            <td><?= $article->id ?></td>
            <td><?= $this->Html->link($article->user_id,['controller' =>'users' ,'action' => 'profile', $article->user_id]) ?></td>
            <td><?= $this->Html->link($article->user->username,['controller' =>'users' ,'action' => 'profile', $article->user_id]) ?></td>
            <td><?= $this->Html->link($article->title, ['action' => 'view', $article->id]) ?></td>
            <td>
                <?php $image_number = $article->image_number;   ?>

                <?php if ($image_number != null){
                    echo ($this->Html->image('/img/thumbnail/'.$image_number.".png"));
                }; ?>
            <td><?= $article->created->format(DATE_RFC850) ?></td>
        </tr>
    <?php endforeach; ?>
</table>