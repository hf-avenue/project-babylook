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
        <th>当サイトで用意された実績は</th>
    </tr>

    <?php foreach ($trophies as $trophie): ?>
    <tr>
        <td><?= $trophie->trophie_name ?></td>
    </tr>
    <?php endforeach; ?>

    <tr>
        <th>Id</th>
        <th>投稿者Id</th>
        <th>作品Title</th>
        <th>Created</th>
    </tr>

    <tr>
        <th>貴方が解除した実績は</th>

    </tr>

    <!--$my_scoreと$exam_score両方上回ったトロフィーのみ出すことにするロジック -->
    <?php foreach ($trophies as $trophie): ?>
        <tr>
            <td><?php if($exam_score >= $trophie->give_score && $my_score >= $trophie->take_score){
                    echo "ID:". $trophie->id;
                    echo $trophie->trophie_name;
                }
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <!-- ここまで -->

    <!-- 自作投稿 -->
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
    <!-- ここまで -->
</table>