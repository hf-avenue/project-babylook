<meta charset="UTF-8"/>

<h1>Babylook Portal2 proto type system</h1>

<table>
    <tr>
        <th><h5>あなたの名前：</h5></th><th><h5><?= $users->user_name ?></h5></th>
    </tr>
    <tr>
        <th><?= $this->Html->image('/img/default_icon.jpg'); ?></th>
    </tr>
    <tr>
        <th><h6><?= $this->Html->link('プロフィール更新', ['action' => 'edit']) ?></h6></th>
        <th><h6><?= $this->Html->link('画像を投稿', ['controller' =>'articles' ,'action' => 'add']) ?></h6></th>
        <th><h6><?= $this->Html->link('文章を投稿', ['controller' =>'novels' ,'action' => 'add']) ?></h6></th>

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
        <th>当サイトで用意された実績は</th>
    </tr>

    <?php foreach ($trophies as $trophie): ?>
    <tr>
        <td><?= $trophie->trophie_name ?></td>
    </tr>
    <?php endforeach; ?>

    <tr>
        <th><h5>プロフィール</h5></th>
        <th>
            <?php if($profile){
                echo nl2br(h($profile->body));
            } ?>

        </th>
    </tr>


    <tr>
        <th>クリアしたミッションは</th>
    </tr>

    <?php foreach ($missions as $mission): ?>
    <!-- todo:仮仕様として、ユーザーに発行されたミッションの全てを出し、Ｘ／Ｙで進捗率を出しています。％にする場合は応相談でお願いします 2017/06/14-->
            <td>ミッション番号<?= $mission->mission_master->id ?></td>
            <td>ミッション名<?= $mission->mission_master->mission_name  ?></td>
            <td>ミッション内容<?= $mission->mission_master->mission_description ?></td>
            <td>ミッション進捗率<?=$mission->mission_progress?> ／ <?= $mission->mission_master->mission_want_progres ?></td>
            <td>ミッション可否<?php if($mission->mission_completed==1){print "完了！";}else{print "実行中";} ?> </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <th>Id</th>
        <th>作品Title</th>
        <th>サムネイル</th>
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
            <td>
                <?= $this->Html->link($article->title, ['controller' =>'articles' ,'action' => 'view', $article->id]) ?>
            </td>

            <td>

                <?php $image_number = $article->image_number;   ?>

                <?php if ($image_number != null){
                    echo ($this->Html->image('/img/thumbnail/'.$image_number.".png"));
                }; ?>

            </th>

            <td>
                <?= $article->created->format('Y: m: d: H: i') ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <!-- ここまで -->
</table>
