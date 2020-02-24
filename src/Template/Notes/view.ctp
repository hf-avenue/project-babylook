<h1>タイトル：<?= h($note->title) ?></h1>
<h2>作者：<?= $this->Html->link($user->user->username,['controller' =>'users' ,'action' => 'profile', $note->user_id]);?>
    <p>コンテンツ内容：</p>
    <div><pre><?php echo  $note->body; ?></pre></div>
<br/>


<?php echo $this->Html->script('jquery-3.2.1.min.js'); ?>
<div><?php echo $this->Html->image('gj_before.png', ['id'=>$note->id, 'alt' =>'イイネ' ]); ?></div>
<div><?php echo $this->Html->image('gj_after.png', ['id'=>'x', 'alt' =>'イイネ' ]); ?></div>
<script>
    document.getElementById("x").style.display="none";
    $(function() {

        $('#<?= $note->id;?>').click (function () {
            alert("イイネを押しました");
            $.ajax({
                url: "../vote/<?= $note->id;?>",
                type: "post",
                dataType: "html"
            }).done(function() {
                alert("成功しました");
                document.getElementById("<?= $note->id;?>").style.display="none";
                document.getElementById("x").style.display="block";
            });
        });
    });
</script>
<p><small>Created: <?=$note->created->format(DATE_RFC850) ?></small></p>


