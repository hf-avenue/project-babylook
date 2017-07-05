<h1>タイトル：<?= h($novel->title) ?></h1>
<h2>作者：<?= $this->Html->link($user->user->username,['controller' =>'users' ,'action' => 'profile', $novel->user_id]);?>
    <p>コンテンツ内容：</p>
    <div><?php echo  nl2br(h($novel->body)); ?></div>
<br/>


<?php echo $this->Html->script('jquery-3.2.1.min.js'); ?>
<div><?php echo $this->Html->image('gj_before.png', ['id'=>$novel->id, 'alt' =>'イイネ' ]); ?></div>
<div><?php echo $this->Html->image('gj_after.png', ['id'=>'x', 'alt' =>'イイネ' ]); ?></div>
<script>
    document.getElementById("x").style.display="none";
    $(function() {

        $('#<?= $novel->id;?>').click (function () {
            alert("イイネを押しました");
            $.ajax({
                url: "../vote/<?= $novel->id;?>",
                type: "post",
                dataType: "html"
            }).done(function() {
                alert("成功しました");
                document.getElementById("<?= $novel->id;?>").style.display="none";
                document.getElementById("x").style.display="block";
            });
        });
    });
</script>
<p><small>Created: <?=$novel->created->format(DATE_RFC850) ?></small></p>


