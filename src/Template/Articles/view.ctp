<h1>タイトル：<?= h($article->title) ?></h1>
<h2>作者：<?= $this->Html->link($user->user->username,['controller' =>'users' ,'action' => 'profile', $article->user_id]);?></h2>
    <p id="description"><?=nl2br(h($article->body)) ?></p>
<br/>
<?php echo $this->Html->script('jquery-3.2.1.min.js'); ?>

<div><?php echo $this->Html->image('gj_before.png', ['id'=>$article->id, 'alt' =>'イイネ' ]); ?></div>
<div><?php echo $this->Html->image('gj_after.png', ['id'=>'x', 'alt' =>'イイネ' ]); ?></div>
<div>

<?php $image_number = $article->image_number;   ?>
<?php $image_ext = $article->img_ext;   ?>
<?php echo $this->Html->image('/img/deliverable/'.$image_number.".".$image_ext); ?>
</div>
<script>
    document.getElementById("x").style.display="none";
    $(function() {

        $('#<?= $article->id;?>').click (function () {
            alert("イイネを押しました");
            $.ajax({
                url: "../vote/<?= $article->id;?>",
                type: "post",
                dataType: "html"
            }).done(function() {
                alert("成功しました");
                document.getElementById("<?= $article->id;?>").style.display="none";
                document.getElementById("x").style.display="block";
            });
        });
    });
</script>
<p><small>Created: <?= $article->created->format(DATE_RFC850) ?></small></p>


